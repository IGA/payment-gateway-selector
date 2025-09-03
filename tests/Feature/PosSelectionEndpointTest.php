<?php

namespace Tests\Feature;

use Database\Seeders\PosRateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosSelectionEndpointTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PosRateSeeder::class);
    }

    public function test_select_pos_api(): void
    {
        $payload = [
            "amount" => 100.00,
            "installment" => 12,
            "currency" => "TRY",
            "card_type" => "credit"
        ];

        $response = $this->postJson('/api/select-pos', $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'filters' => [
                         'cardType',
                         'cardBrand',
                         'amount',
                         'currency',
                         'installment'
                     ],
                     'overall_min' => [
                         'posName',
                         'price',
                         'commissionRate',
                         'cardType',
                         'cardBrand',
                         'installment',
                         'currency',
                         'payableTotal'
                     ]
                 ]);

        $response->assertJson([
            "filters" => [
                "cardType" => "credit",
                "cardBrand" => null,
                "amount" => 100,
                "currency" => "TRY",
                "installment" => 12
            ],
            "overall_min" => [
                "posName" => "YapiKredi",
                "price" => 3.1,
                "commissionRate" => 0.031,
                "cardType" => "credit",
                "cardBrand" => "world",
                "installment" => 12,
                "currency" => "TRY",
                "payableTotal" => 103.1
            ]
        ]);
    }
}
