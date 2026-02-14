<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
         User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(), // jadi verified
            'password' => Hash::make('123456'),
            'remember_token' => Str::random(10),
        ]);
    }
}
