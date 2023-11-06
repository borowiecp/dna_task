<?php

namespace App\Services;

use App\Http\Resources\AccountDto;
use App\Http\Resources\MerchantDto;
use App\Http\Resources\PaymentDto;
use App\Models\Payment;
use Exception;
use Ramsey\Uuid\Uuid;

readonly class PaymentService
{
    public function __construct(
        private MerchantService $merchantService,
        private AccountService $accountService,
    ) {}

    /**
     * @throws Exception
     */
    public function addPayment(PaymentDto $paymentDto): PaymentDto {
        $merchant = $this->merchantService->getMerchant($paymentDto->getMerchantId());
        $account = $this->accountService->getAccount($paymentDto->getAccountId());

        if ($account->getBalance() < $paymentDto->getAmount()) {
            throw new Exception("insufficient funds");
        }

        $this->accountService->decreaseBalance($account->getAccountId(), $paymentDto->getAmount());
        $payment = $this->toPayment($paymentDto, $account, $merchant);
        $payment->save();

        return $this->paymentToPaymentDto($payment);
    }

    private function paymentToPaymentDto(Payment $payment): PaymentDto {
        return new PaymentDto(
            $payment->paymentId,
            $payment->accountId,
            $payment->merchantId,
            $payment->amount,
        );
    }

    private function toPayment(
        PaymentDto $paymentDto,
        AccountDto $accountDto,
        MerchantDto $merchantDto
    ): Payment {
        return new Payment([
            'paymentId' => Uuid::uuid4(),
            'accountId' => $accountDto->getAccountId(),
            'merchantId' => $merchantDto->getMerchantId(),
            'amount' => $paymentDto->getAmount(),
        ]);
    }
}
