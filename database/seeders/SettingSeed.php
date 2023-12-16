<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Setting::create([
                'title' => 'invoices_price', 'value' => json_encode([
                    'cutting' => rand(22000, 35000),
                    'pvc' => [
                        'size_1' => rand(12000, 16000),
                        'size_2' => rand(11000, 14000),
                    ],
                    'chamfer' => rand(12000, 15000),
                    'groove' => rand(12000, 18000),
                ]), 'author_id' => $i,
            ]);
        }
    }
}
