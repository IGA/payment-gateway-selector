<?php

namespace App\DTOs;

use JsonSerializable;

class PosSelectionResult implements JsonSerializable
{
    public function __construct(
        public string $posName,
        public float $cost,
        public float $commissionRate,
        public int $priority,

        public string $cardType,
        public string $cardBrand,
        public int $installment,
        public string $currency,
        public float $payableTotal,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'posName' => $this->posName,
            'price' => $this->cost,
            'commissionRate' => $this->commissionRate,
            'cardType' => $this->cardType,
            'cardBrand' => $this->cardBrand,
            'installment' => $this->installment,
            'currency' => $this->currency,
            'payableTotal' => $this->payableTotal,
        ];
    }
}