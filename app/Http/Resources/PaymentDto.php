<?php

namespace App\Http\Resources;

class PaymentDto
{
    private ?string $paymentId;
    private ?string $accountId;
    private ?string $merchantId;
    private ?float $amount;

    public function __construct(
        ?string $paymentId = null,
        ?string $accountId = null,
        ?string $merchantId = null,
        ?float $amount = null
    ) {
        $this->paymentId = $paymentId;
        $this->accountId = $accountId;
        $this->merchantId = $merchantId;
        $this->amount = $amount;
    }

    public function toResponse(): array {
        return [
            'payment_id' => $this->paymentId,
            'account_id' => $this->accountId,
            'merchant_id' => $this->merchantId,
            'amount' => $this->amount,
        ];
    }

    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    public function setPaymentId(?string $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function setAccountId(?string $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getMerchantId(): ?string
    {
        return $this->merchantId;
    }

    public function setMerchantId(?string $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
    }
}
