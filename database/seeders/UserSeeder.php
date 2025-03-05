<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $managerRole = Role::where('slug', 'production-manager')->first();
        $operatorRole = Role::where('slug', 'operator')->first();

        // Create a production manager
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role_id' => $managerRole->id,
        ]);

        // Create operators
        User::create([
            'name' => 'Operator 1',
            'email' => 'operator1@example.com',
            'password' => Hash::make('password'),
            'role_id' => $operatorRole->id,
        ]);

        User::create([
            'name' => 'Operator 2',
            'email' => 'operator2@example.com',
            'password' => Hash::make('password'),
            'role_id' => $operatorRole->id,
        ]);
    }
}
