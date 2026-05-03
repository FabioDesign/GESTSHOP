<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['number' => '0102030405'],
            [
                'lastname' => 'OGOU',
                'firstname' => 'Fabrice',
                'gender' => 'M',
                'email' => 'fabio225@yopmail.com',
                'password' => Hash::make('Azerty@123'),
                'password_at' => now(),
                'status' => 1,
                'created_by' => 1,
                'profile_id' => 1,
            ]
        );
    }
}