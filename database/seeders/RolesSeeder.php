<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Crea los roles si no existen
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'client']);
    }
}
