<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([

         'username' => 'heyam',
    'email' => 'admin@example.com',
    'password' => Hash::make('123456'),
    'role' => 'مسؤول',
    'phone_number' => '01234567890',
    'full_name' => 'هيام رمضان', //
        ]);

    }
}
