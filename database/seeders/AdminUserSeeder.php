<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = \App\Models\Company::firstOrCreate(
            ['name' => 'Demo Organisatie'],
            [
                'company_type' => 'cleaning',
                'subscription_plan' => 'starter',
                'subscription_status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
                'max_users' => \App\Models\Company::PLANS['starter']['max_users'],
                'max_locations' => \App\Models\Company::PLANS['starter']['max_locations'],
                'max_storage_gb' => \App\Models\Company::PLANS['starter']['max_storage_gb'],
                'is_active' => true,
            ]
        );

        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'department' => 'Management',
            'is_active' => true,
            'company_id' => $company->id,
        ]);

        \App\Models\User::create([
            'name' => 'John Employee',
            'email' => 'employee@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'department' => 'Operations',
            'is_active' => true,
            'company_id' => $company->id,
        ]);

        \App\Models\User::create([
            'name' => 'Jane Worker',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'department' => 'Cleaning',
            'is_active' => true,
            'company_id' => $company->id,
        ]);
    }
}
