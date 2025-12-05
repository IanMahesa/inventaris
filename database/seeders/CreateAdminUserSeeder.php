<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    public function run()
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123')
            ]
        );
        $superAdmin->assignRole('Super Admin');

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123')
            ]
        );
        $admin->assignRole('Admin');

        // Operator
        $operator = User::firstOrCreate(
            ['email' => 'operator@gmail.com'],
            [
                'name' => 'Operator',
                'password' => Hash::make('password123')
            ]
        );
        $operator->assignRole('Operator');

        // Viewer
        $viewer = User::firstOrCreate(
            ['email' => 'viewer@gmail.com'],
            [
                'name' => 'Viewer',
                'password' => Hash::make('password123')
            ]
        );
        $viewer->assignRole('Viewer');

        // Auditor
        $auditor = User::firstOrCreate(
            ['email' => 'auditor@gmail.com'],
            [
                'name' => 'Auditor',
                'password' => Hash::make('password123')
            ]
        );
        $auditor->assignRole('Auditor');
    }
}
