<?php

namespace Tests\Unit;

use App\DTOs\PaymentDTO;
use App\DTOs\PosSelectionResult;
use App\Enums\CardBrand;
use App\Enums\CardType;
use App\Enums\Currency;
use App\Models\PosRate;
use App\Repositories\PosRateRepository;
use App\Services\Pos\RateService;
use App\Services\Pos\SelectionService;
use Database\Seeders\PosRateSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PosSelectionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PosRateSeeder::class);
    }

    /**
     * A basic unit test example.
     */
    public function test_data(): void
    {
        $case1Count = PosRate::query()->where('pos_name', 'Garanti')->count();
        $this->assertGreaterThan(0, $case1Count);

        $case2Count = PosRate::all()->count();
        $this->assertEquals(50, $case2Count);
    }

    public function test_selectProvider_case1()
    {
        $paymentDto = new PaymentDTO(
            cardType: CardType::CREDIT->value,
            cardBrand: null,
            amount: 362.22,
            currency: Currency::TRY->value,
            installment: 6,
        );

        $posRateRepository = new PosRateRepository();
        $rateService = new RateService($posRateRepository);
        $selectionService = new SelectionService($rateService);

        $result = $selectionService->selectProvider($paymentDto);
        $expect = new PosSelectionResult(
            posName: "KuveytTurk",
            cost: 9.42,
            commissionRate: 0.026,
            priority: 4,
            cardType: "credit",
            cardBrand: "saglam",
            installment: 6,
            currency: "TRY",
            payableTotal: 371.64,
        );

        $this->assertEquals($result, $expect);
    }

    public function test_selectProvider_case2()
    {
        $paymentDto = new PaymentDTO(
            cardType: CardType::CREDIT->value,
            cardBrand: CardBrand::BONUS->value,
            amount: 395.00,
            currency: Currency::USD->value,
            installment: 3,
        );

        $posRateRepository = new PosRateRepository();
        $rateService = new RateService($posRateRepository);
        $selectionService = new SelectionService($rateService);

        $result = $selectionService->selectProvider($paymentDto);
        $expect = new PosSelectionResult(
            posName: "Denizbank",
            cost: 12.37,
            commissionRate: 0.0310,
            priority: 5,
            cardType: "credit",
            cardBrand: "bonus",
            installment: 3,
            currency: "USD",
            payableTotal: 407.37,
        );

        $this->assertEquals($result, $expect);
    }

    public function test_selectProvider_case3   ()
    {
        $paymentDto = new PaymentDTO(
            cardType: CardType::CREDIT->value,
            cardBrand: null,
            amount: 60.00,
            currency: Currency::TRY->value,
            installment: 3,
        );

        $posRateRepository = new PosRateRepository();
        $rateService = new RateService($posRateRepository);
        $selectionService = new SelectionService($rateService);

        $result = $selectionService->selectProvider($paymentDto);
        $expect = new PosSelectionResult(
            posName: "QNB",
            cost: 1.37,
            commissionRate: 0.0229,
            priority: 6,
            cardType: "credit",
            cardBrand: "cardfinans",
            installment: 3,
            currency: "TRY",
            payableTotal: 61.37,
        );

        $this->assertEquals($result, $expect);
    }

    public function test_selectProvider_case4   ()
    {
        $paymentDto = new PaymentDTO(
            cardType: CardType::CREDIT->value,
            cardBrand: null,
            amount: 100.00,
            currency: Currency::TRY->value,
            installment: 12,
        );

        $posRateRepository = new PosRateRepository();
        $rateService = new RateService($posRateRepository);
        $selectionService = new SelectionService($rateService);

        $result = $selectionService->selectProvider($paymentDto);
        $expect = new PosSelectionResult(
            posName: "YapiKredi",
            cost: 3.10,
            commissionRate: 0.0310,
            priority: 7,
            cardType: "credit",
            cardBrand: "world",
            installment: 12,
            currency: "TRY",
            payableTotal: 103.1,
        );

        $this->assertEquals($result, $expect);
    }

}
