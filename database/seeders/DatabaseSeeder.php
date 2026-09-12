<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);

        User::updateOrCreate(
            ['email' => 'officer@example.com'],
            ['name' => 'Loan Officer', 'role' => 'loan_officer', 'password' => Hash::make('12345678')],
        );

        User::updateOrCreate(
            ['email' => 'cashier@example.com'],
            ['name' => 'Cashier', 'role' => 'cashier', 'password' => Hash::make('12345678')],
        );

        $customer = Customer::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'customer_code' => 'CUS-DEMO01',
                'first_name' => 'Demo',
                'last_name' => 'Customer',
                'phone' => '0123456789',
                'city' => 'Phnom Penh',
                'status' => 'Active',
            ],
        );

        User::updateOrCreate(
            ['email' => $customer->email],
            ['name' => 'Demo Customer', 'role' => 'customer', 'password' => Hash::make('12345678')],
        );
    }
}
