<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stall;

class StallSeeder extends Seeder
{
    /**
     * Seed 100 stalls across 5 zones in Muthurwa Market.
     * Uses updateOrCreate so re-running the seeder is safe.
     *
     * Zones (20 stalls each):
     *   Zone A – Fresh Produce
     *   Zone B – Cereals & Grains
     *   Zone C – Clothing & Textiles
     *   Zone D – Electronics & Hardware
     *   Zone E – General Merchandise
     */
    public function run(): void
    {
        $zones = [
            1 => ['name' => 'Zone A', 'desc' => 'Fresh Produce Section',         'price' => 1.00],
            2 => ['name' => 'Zone B', 'desc' => 'Cereals & Grains Section',      'price' => 1.00],
            3 => ['name' => 'Zone C', 'desc' => 'Clothing & Textiles Section',   'price' => 1.00],
            4 => ['name' => 'Zone D', 'desc' => 'Electronics & Hardware Section','price' => 1.00],
            5 => ['name' => 'Zone E', 'desc' => 'General Merchandise Section',   'price' => 1.00],
        ];

        $count = 0;

        for ($i = 1; $i <= 100; $i++) {
            $zoneIndex = (int) ceil($i / 20); // 1-5
            $zone      = $zones[$zoneIndex];

            Stall::updateOrCreate(
                ['stall_number' => 'STALL-' . str_pad($i, 3, '0', STR_PAD_LEFT)],
                [
                    'zone'          => $zone['name'],
                    'location_desc' => 'Muthurwa Market – ' . $zone['desc'],
                    'price'         => $zone['price'],
                    'latitude'      => -1.2921,
                    'longitude'     => 36.8219,
                    'status'        => 'available',
                    'is_blocked'    => false,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            );

            $count++;
        }

        $this->command->info("✅  {$count} stalls seeded successfully.");
        $this->command->table(
            ['Zone', 'Description', 'Stalls', 'Daily Price (KES)'],
            [
                ['Zone A', 'Fresh Produce',          'STALL-001 → STALL-020', '1.00'],
                ['Zone B', 'Cereals & Grains',       'STALL-021 → STALL-040', '1.00'],
                ['Zone C', 'Clothing & Textiles',    'STALL-041 → STALL-060', '1.00'],
                ['Zone D', 'Electronics & Hardware', 'STALL-061 → STALL-080', '1.00'],
                ['Zone E', 'General Merchandise',    'STALL-081 → STALL-100', '1.00'],
            ]
        );
    }
}