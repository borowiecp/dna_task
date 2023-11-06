<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Merchant;
use App\Models\Payment;
use App\Models\User;
use App\Services\AccountService;
use App\Services\MerchantService;
use App\Services\UserService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;
use Throwable;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    private UserService $userService;
    private MerchantService $merchantService;
    private AccountService $accountService;

    public function setUp(): void {
        parent::setUp();

        $this->userService = $this->app->make('App\Services\UserService');
        $this->merchantService = $this->app->make('App\Services\MerchantService');
        $this->accountService = $this->app->make('App\Services\AccountService');

        User::query()->delete();
        Merchant::query()->delete();
        Payment::query()->delete();
        Account::query()->delete();
    }

    private function thereIsMerchant(): string {
        $merchant = $this->merchantService->addMerchant('DNA');
        return $merchant->getMerchantId();
    }

    /**
     * @throws Exception
     */
    private function thereIsUser(): string {
        $user = $this->userService->addUser(
            'Jan Kowalski',
            'jan.kowalski@digitalnewagency.com',
        );

        return $user->getUserId();
    }

    /**
     * @throws Exception
     */
    private function userHasPositiveAccountBalance(string $userId): float {
        $accountId = $this->accountService->getAccountForUser($userId)->getAccountId();
        return $this->accountService->increaseBalance($accountId, 100.0)->getBalance();
    }

    /**
     * @throws Throwable
     */
    public function test_should_save_payment_transaction(): void
    {
        // given
        $merchantId = $this->thereIsMerchant();
        $userId = $this->thereIsUser();
        $initialBalance = $this->userHasPositiveAccountBalance($userId);
        $accountId = $this->accountService->getAccountForUser($userId)->getAccountId();

        // when
        $amount = 10.0;
        $payload = [
            'account_id' => $accountId,
            'merchant_id' => $merchantId,
            'amount' => $amount,
        ];

        $response = $this->json('post', 'api/transactions', $payload)
            ->assertStatus(ResponseAlias::HTTP_OK)
            ->json();

        // then
        self::assertEquals($accountId, $response['account_id']);
        self::assertEquals($merchantId, $response['merchant_id']);
        self::assertEquals($amount, $response['amount']);

        $storedPayment = Payment::query()->where('paymentId', $response['payment_id'])->first();
        self::assertEquals($accountId, $storedPayment->accountId);
        self::assertEquals($merchantId, $storedPayment->merchantId);
        self::assertEquals($amount, $storedPayment->amount);

        $account = Account::query()->where('accountId', $accountId)->first();
        self::assertEquals($initialBalance - $amount, $account->balance);
    }
}
