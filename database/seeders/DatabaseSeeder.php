<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Rate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => 'Petugas',
            'username' => 'petugas',
            'password' => Hash::make('petugas1234'),
        ]);

        Rate::create([
            'type' => 'Motor',
            'base_price' => 2000,
            'price_per_hour' => 2000,
        ]);

        Rate::create([
            'type' => 'Mobil',
            'base_price' => 3000,
            'price_per_hour' => 3000,
        ]);
    }
}
