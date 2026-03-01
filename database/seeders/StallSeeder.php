<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StallSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {
            DB::table('stalls')->insert([
                'stall_number' => 'STALL-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'zone' => 'Zone ' . ceil($i / 20), // 5 zones (20 stalls each)
                'location_desc' => 'Muthurwa Market Section ' . ceil($i / 20),
                'latitude' => -1.2921,
                'longitude' => 36.8219,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}