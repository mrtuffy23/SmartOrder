<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kita bikin data dummy manual array
        $userData = [
            [
                'name' => 'Si Superadmin',
                'email' => 'super@indotex.com',
                'role' => 'superadmin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Si Admin',
                'email' => 'admin@indotex.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Staff Gudang',
                'email' => 'gudang@indotex.com',
                'role' => 'gudang',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Staff Produksi',
                'email' => 'produksi@indotex.com',
                'role' => 'produksi',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Staff Laborat',
                'email' => 'laborat@indotex.com',
                'role' => 'laborat',
                'password' => Hash::make('password'),
            ]
        ];

        foreach ($userData as $val) {
            User::updateOrCreate(
                ['email' => $val['email']],
                $val
            );
        }
    }
}