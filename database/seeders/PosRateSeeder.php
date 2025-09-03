<?php

namespace Database\Seeders;

use App\Models\PosRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PosRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = base_path('database/seeders/data/pos_rates.csv');

        if (!file_exists($file)) {
            $this->command->error("CSV file not found: $file");
            return;
        }

        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                PosRate::create([
                    'pos_name'        => $row[1],
                    'card_type'       => $row[2],
                    'card_brand'      => $row[3],
                    'installment'     => (int) $row[4],
                    'currency'        => $row[5],
                    'commission_rate' => (float) $row[6],
                    'min_fee'         => (float) $row[7],
                    'priority'        => (int) $row[8],
                ]);
            }
            fclose($handle);
        }
    }
}
