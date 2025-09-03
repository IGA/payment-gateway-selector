<?php

namespace App\Repositories;

use App\Enums\CardBrand;
use App\Enums\CardType;
use App\Models\PosRate;
use Illuminate\Database\Eloquent\Collection;

class PosRateRepository
{
    public function findRates(
        CardType $cardType,
        string $currency,
        int $installment,
        ?CardBrand $cardBrand = null
    ): Collection
    {
        return PosRate::query()
            ->where('card_type', $cardType->value)
            ->where('currency', $currency)
            ->where('installment', $installment)
            ->when($cardBrand, function ($query, $brand) {
                $query->where('card_brand', $brand->value);
            })
            ->get();
    }
}