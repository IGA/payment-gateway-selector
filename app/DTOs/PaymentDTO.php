<?php

namespace App\DTOs;

class PaymentDTO
{
    public function __construct(
        public string $cardType,
        public ?string $cardBrand,
        public float $amount,
        public string $currency,
        public int $installment
    ) {}
}