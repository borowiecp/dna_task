<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Merchant;
use App\Models\User;
use App\Services\MerchantService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    private MerchantService $merchantService;
    public function setUp(): void {
        parent::setUp();

        $this->merchantService = $this->app->make('App\Services\MerchantService');

        User::query()->delete();
        Merchant::query()->delete();
        Account::query()->delete();
    }

    public function test_should_save_user() {
        // given
        $merchantId = $this->thereIsMerchant();
        $fullName = 'John Doe';
        $email = 'john.doe@digitalnewagency.com';

        $payload = [
            'fullName' => $fullName,
            'email' => $email,
            'merchantId' => $merchantId,
        ];

        // when
        $response = $this->json('post', 'api/users', $payload)
            ->assertStatus(ResponseAlias::HTTP_OK)
            ->json();

        // then
        $storedUser = User::query()->where('userId', $response['userId'])->first();
        self::assertEquals($fullName, $storedUser->fullName);
        self::assertEquals($merchantId, $storedUser->merchantId);
        self::assertEquals($email, $storedUser->email);

        $storedAccount = Account::query()->where('userId', $response['userId'])->first();
        self::assertEquals(0.0, $storedAccount->balance);
        self::assertNotNull($storedAccount->accountId);
    }

    private function thereIsMerchant(): string {
        $merchant = $this->merchantService->addMerchant('DNA');
        return $merchant->getMerchantId();
    }
}
