<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name'       => 'Super Admin',
                'password'   => Hash::make('password'),
                'role'       => 'super_admin',
                'company_id' => null,
            ]
        );
    }
}
