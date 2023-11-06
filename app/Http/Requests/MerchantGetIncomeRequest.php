<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MerchantGetIncomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'merchant_id' => 'required|string|exists:merchants,merchantId',
            'from' => 'required|date|date_format:Y-m-d H:i:s',
            'to' => 'required|date|date_format:Y-m-d H:i:s',
        ];
    }
}
