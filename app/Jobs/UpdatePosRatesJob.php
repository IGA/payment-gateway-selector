<?php

namespace App\Jobs;

use App\Models\PosRate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class UpdatePosRatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $endpoint = config('services.pos_rates.endpoint');

        try {
            $response = Http::get($endpoint);
            \Log::info("Mock API response", $response->json());

            if ($response->successful()) {
                $posRates = $response->json();

                foreach ($posRates as $posRate) {
                    PosRate::updateOrCreate(
                        [
                            'pos_name' => $posRate['pos_name'],
                            'card_type' => $posRate['card_type'],
                            'card_brand' => $posRate['card_brand'],
                            'installment' => $posRate['installment'],
                            'currency' => $posRate['currency'],
                        ],
                        [
                            'commission_rate' => $posRate['commission_rate'],
                            'min_fee' => $posRate['min_fee'],
                            'priority' => $posRate['priority'],
                        ]
                    );
                }
            } else {
                \Log::error("An error acquired while fetching pos rates", [
                    'response' => $response,
                    'endpoint'   => $endpoint,
                ]);
            }
        } catch (\Throwable $exception) {
            \Log::error($exception->getMessage(), ['exception' => $exception]);
        }
    }
}
