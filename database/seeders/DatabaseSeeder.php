<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

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
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'phone_number' => '123456789',
            'is_admin' => '1',
            'password' => Crypt::encryptString('12345678'),
        ]);

        \App\Models\User::create([
            'name' => 'Service Provider',
            'email' => 'sp@sp.com',
            'phone_number' => '1234567890',
            'is_admin' => '0',
            'password' => Crypt::encryptString('12345678'),
        ]);

        \App\Models\Category::create([
            'service_provider_id' => '2',
            'name' => 'Furniture',
        ]);

        \App\Models\Item::create([
            'service_provider_id' => '2',
            'category_id' => '1',
            'name' => 'Table',
        ]);

        \App\Models\Address::create([
            'service_provider_id' => '2',
            'province' => 'Gandaki',
            'district' => 'Kaski',
            'city' => 'Pokhara',
        ]);

        \App\Models\Address::create([
            'service_provider_id' => '2',
            'province' => 'Bagmati',
            'district' => 'Kathmandu',
            'city' => 'Kathmandu',
        ]);

        \App\Models\Address::create([
            'service_provider_id' => '2',
            'province' => 'Gandaki',
            'district' => 'Lamjung',
            'city' => 'Beshisahar',
        ]);
    }
}
