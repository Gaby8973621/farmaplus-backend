<?php

// database/seeders/UsersSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate([
            'email' => 'stefany@gmail.com',
        ], [
            'name' => 'stefany',
            'password' => bcrypt('admin123'),
        ]);
        $admin->assignRole('admin');

        $client = User::firstOrCreate([
            'email' => 'luis@gmail.com',
        ], [
            'name' => 'Cliente',
            'password' => bcrypt('client123'),
        ]);
        $client->assignRole('client');

        $client = User::firstOrCreate([
            'email' => 'ellie@gmail.com',
        ], [
            'name' => 'Cliente',
            'password' => bcrypt('client123'),
        ]);
        $client->assignRole('client');
    }
}
