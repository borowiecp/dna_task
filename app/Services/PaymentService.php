<?php

namespace App\Services;

use App\Http\Resources\MerchantDto;
use App\Http\Resources\PaymentDto;
use App\Http\Resources\UserDto;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

readonly class PaymentService
{
    public function __construct(
        private UserService $userService,
        private MerchantService $merchantService,
        private AccountService $accountService,
    ) {}

    /**
     * @throws Exception
     */
    public function addPayment(PaymentDto $paymentDto): PaymentDto {
        $user = $this->userService->getUser($paymentDto->getUserId());
        $merchant = $this->merchantService->getMerchant($paymentDto->getMerchantId());
        $account = $this->accountService->getAccountForUser($paymentDto->getUserId());

        if ($account->getBalance() < $paymentDto->getAmount()) {
            throw new Exception("insufficient funds");
        }

        $this->accountService->decreaseBalance($account->getAccountId(), $paymentDto->getAmount());
        $payment = $this->toPayment($paymentDto, $user, $merchant);
        $payment->save();

        return $this->paymentToPaymentDto($payment);
    }

    private function paymentToPaymentDto(Payment $payment): PaymentDto {
        return new PaymentDto(
            $payment->paymentId,
            $payment->userId,
            $payment->merchantId,
            $payment->amount,
        );
    }

    private function toPayment(
        PaymentDto $paymentDto,
        UserDto $userDto,
        MerchantDto $merchantDto
    ): Payment {
        return new Payment([
            'paymentId' => Uuid::uuid4(),
            'userId' => $userDto->getUserId(),
            'merchantId' => $merchantDto->getMerchantId(),
            'amount' => $paymentDto->getAmount(),
        ]);
    }
}
