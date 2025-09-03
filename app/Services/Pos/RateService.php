<?php

namespace App\Services\Pos;

use App\Enums\CardBrand;
use App\Enums\CardType;
use App\Repositories\PosRateRepository;
use Illuminate\Database\Eloquent\Collection;

class RateService
{
    public function __construct(public PosRateRepository $posRateRepository) {}

    public function getRates(
        CardType $cardType,
        string $currency,
        int $installment,
        ?CardBrand $cardBrand = null,
    ): Collection
    {
        return $this->posRateRepository->findRates($cardType, $currency, $installment, $cardBrand);
    }
}