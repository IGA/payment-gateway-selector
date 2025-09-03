<?php

namespace App\Services\Pos;

use App\DTOs\PaymentDTO;
use App\DTOs\PosSelectionResult;
use App\Enums\CardBrand;
use App\Enums\CardType;
use App\Enums\Currency;
use App\Models\PosRate;

class SelectionService
{
    public function __construct(public RateService $rateService) {}

    public function selectProvider(PaymentDTO $paymentDTO): ?PosSelectionResult
    {
        $cardType = CardType::tryFrom($paymentDTO->cardType);
        $cardBrand = $paymentDTO->cardBrand ? CardBrand::tryFrom($paymentDTO->cardBrand) : null;
        $currency = Currency::tryFrom($paymentDTO->currency);

        $rates = $this->rateService->getRates(
            $cardType,
            $paymentDTO->currency,
            $paymentDTO->installment,
            $cardBrand
        );

        if ($rates->isEmpty()) {
            return null;
        }

        return $rates->map(function ($rate) use ($paymentDTO, $currency) {
            $cost = round($this->calculateCost($currency, $paymentDTO->amount, $rate), 2);
            $payableTotal = round($paymentDTO->amount + $cost, 2);
            return new PosSelectionResult(
                posName: $rate->pos_name,
                cost: $cost,
                commissionRate: $rate->commission_rate,
                priority: $rate->priority,
                cardType: $rate->card_type,
                cardBrand: $rate->card_brand,
                installment: $rate->installment,
                currency: $rate->currency,
                payableTotal: $payableTotal
            );
        })
            ->sortBy([
                ['cost', 'asc'],
                ['priority', 'desc'],
                ['commissionRate', 'asc'],
                ['posName', 'asc'],
            ])
            ->first();
    }

    public function calculateCost(Currency $currency, float $amount, PosRate $posRate): float
    {
        return match ($currency) {
            Currency::TRY => max($amount * $posRate->commission_rate, $posRate->min_fee),
            Currency::USD => max($amount * $posRate->commission_rate * 1.01, $posRate->min_fee),
        };
    }
}