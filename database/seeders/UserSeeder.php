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
            'phone' => '01000000000',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'محمد أحمد - المقاول الأول',
            'phone' => '01001111111',
            'role' => 'contractor',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'علي محمود - المقاول الثاني',
            'phone' => '01002222222',
            'role' => 'contractor',
            'password' => Hash::make('password'),
        ]);
    }
}
