<?php

namespace App\Services;

use App\Http\Resources\MerchantDto;
use App\Models\Merchant;
use Exception;
use Ramsey\Uuid\Uuid;

readonly class MerchantService
{
    public function addMerchant(string $name): MerchantDto {
        $merchant = new Merchant([
            'merchantId' => Uuid::uuid4(),
            'name' => $name,
        ]);

        $merchant->save();

        return $this->merchantToMerchantDto($merchant);
    }

    public function getMerchant(string $merchantId): MerchantDto {
        $merchant = Merchant::query()->where('merchantId', $merchantId)->first();

        if (is_null($merchant)) {
            throw new Exception("Merchant with id " . $merchantId . " not found");
        }

        return $this->merchantToMerchantDto($merchant);
    }

    public function merchantToMerchantDto(Merchant $merchant): MerchantDto {
        return new MerchantDto($merchant->merchantId, $merchant->name);
    }
}
