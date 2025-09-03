<?php

namespace App\Http\Requests;

use App\DTOs\PaymentDTO;
use App\Enums\CardBrand;
use App\Enums\CardType;
use App\Enums\Currency;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SelectPosRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'card_type' => 'required|string|in:' . implode(',', array_column(CardType::cases(), 'value')),
            'card_brand' => 'nullable|string|in:' . implode(',', array_column(CardBrand::cases(), 'value')),
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|in:' . implode(',', array_column(Currency::cases(), 'value')),
            'installment' => 'nullable|integer|min:1',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }

    public function toDTO(): PaymentDTO
    {
        return new PaymentDTO(
            cardType: $this->input('card_type'),
            cardBrand: $this->input('card_brand'),
            amount: $this->input('amount'),
            currency: $this->input('currency'),
            installment: $this->input('installment', 1)
        );
    }
}