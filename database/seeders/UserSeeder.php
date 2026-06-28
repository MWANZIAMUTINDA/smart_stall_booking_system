<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed 3 admins and 3 officers into the users table.
     */
    public function run(): void
    {
        // ─── ADMINS ──────────────────────────────────────────────────────────
        $admins = [
            [
                'name'               => 'Admin One',
                'username'           => 'admin1',
                'email'              => 'admin1@muthurwa.go.ke',
                'phone_number'       => '0700000001',
                'role'               => 'admin',
                'status'             => 'active',
                'account_restriction'=> 'none',
                'password'           => Hash::make('Admin@1234'),
                'email_verified_at'  => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'name'               => 'Admin Two',
                'username'           => 'admin2',
                'email'              => 'admin2@muthurwa.go.ke',
                'phone_number'       => '0700000002',
                'role'               => 'admin',
                'status'             => 'active',
                'account_restriction'=> 'none',
                'password'           => Hash::make('Admin@1234'),
                'email_verified_at'  => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'name'               => 'Admin Three',
                'username'           => 'admin3',
                'email'              => 'admin3@muthurwa.go.ke',
                'phone_number'       => '0700000003',
                'role'               => 'admin',
                'status'             => 'active',
                'account_restriction'=> 'none',
                'password'           => Hash::make('Admin@1234'),
                'email_verified_at'  => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ];

        // ─── OFFICERS ─────────────────────────────────────────────────────────
        $officers = [
            [
                'name'               => 'Officer One',
                'username'           => 'officer1',
                'email'              => 'officer1@muthurwa.go.ke',
                'phone_number'       => '0711000001',
                'role'               => 'officer',
                'status'             => 'active',
                'account_restriction'=> 'none',
                'password'           => Hash::make('Officer@1234'),
                'email_verified_at'  => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'name'               => 'Officer Two',
                'username'           => 'officer2',
                'email'              => 'officer2@muthurwa.go.ke',
                'phone_number'       => '0711000002',
                'role'               => 'officer',
                'status'             => 'active',
                'account_restriction'=> 'none',
                'password'           => Hash::make('Officer@1234'),
                'email_verified_at'  => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'name'               => 'Officer Three',
                'username'           => 'officer3',
                'email'              => 'officer3@muthurwa.go.ke',
                'phone_number'       => '0711000003',
                'role'               => 'officer',
                'status'             => 'active',
                'account_restriction'=> 'none',
                'password'           => Hash::make('Officer@1234'),
                'email_verified_at'  => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ];

        foreach (array_merge($admins, $officers) as $userData) {
            // Use updateOrCreate so re-running the seeder won't duplicate records
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('✅  3 Admins and 3 Officers seeded successfully.');
        $this->command->table(
            ['Role', 'Name', 'Email', 'Password'],
            array_map(fn($u) => [
                strtoupper($u['role']),
                $u['name'],
                $u['email'],
                $u['role'] === 'admin' ? 'Admin@1234' : 'Officer@1234',
            ], array_merge($admins, $officers))
        );
    }
}
