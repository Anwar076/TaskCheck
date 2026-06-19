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
        $company = \App\Models\Organisation\Company::firstOrCreate(
            ['name' => 'Demo Organisatie'],
            [
                'company_type' => 'cleaning',
                'address' => 'Demostraat 1, 1011 AB Amsterdam',
                'phone' => '0201234567',
                'email' => 'demo@taskcheck.test',
                'working_hours' => \App\Models\Organisation\Company::defaultWorkingHours(),
                'subscription_plan' => 'starter',
                'subscription_status' => 'trial',
                'trial_ends_at' => now()->addDays(14),
                'max_users' => \App\Models\Organisation\Company::PLANS['starter']['max_users'],
                'max_locations' => \App\Models\Organisation\Company::PLANS['starter']['max_locations'],
                'max_storage_gb' => \App\Models\Organisation\Company::PLANS['starter']['max_storage_gb'],
                'is_active' => true,
                'onboarding_step' => \App\Models\Organisation\Company::ONBOARDING_STEP_COMPLETED,
                'onboarding_completed_at' => now(),
            ]
        );

        \App\Models\Organisation\User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'department' => 'Management',
            'is_active' => true,
            'company_id' => $company->id,
        ]);

        \App\Models\Organisation\User::create([
            'name' => 'John Employee',
            'email' => 'employee@example.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'department' => 'Operations',
            'is_active' => true,
            'company_id' => $company->id,
        ]);

        \App\Models\Organisation\User::create([
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
