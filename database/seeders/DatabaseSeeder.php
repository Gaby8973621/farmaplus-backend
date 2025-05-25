<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecuta primero los roles
        $this->call([
            RolesSeeder::class,
        ]);

        // Crea un usuario con rol 'client'
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('12345678'), // contraseña segura
        ]);

        // Asignar rol al usuario
        $user->assignRole('client');
    }
}
