<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Production Manager',
            'slug' => 'production-manager',
            'description' => 'Can create work orders, assign operators, update status, and view reports',
        ]);

        Role::create([
            'name' => 'Operator',
            'slug' => 'operator',
            'description' => 'Can view assigned work orders and update their status',
        ]);
    }
}
