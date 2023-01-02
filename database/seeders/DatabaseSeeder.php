<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone_number' => '123456789',
            'type' => 'admin',
            'password' => '123456789',
        ]);

        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test1@example.com',
            'phone_number' => '1234156789',
            'type' => 'admin',
            'password' => '123456789',
        ]);

        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'phone_number' => '1234526789',
            'type' => 'admin',
            'password' => '123456789',
        ]);
    }
}