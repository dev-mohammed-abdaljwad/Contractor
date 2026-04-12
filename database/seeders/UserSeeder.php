<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'phone' => '01029354974',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);
    }
}
