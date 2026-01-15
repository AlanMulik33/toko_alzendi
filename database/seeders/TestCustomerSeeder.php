<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class TestCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = Customer::create([
            'name' => 'Test Customer',
            'username' => 'testcust',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
        ]);

        $customer->addresses()->create([
            'address' => 'Jl. Test No. 123, Kota Test, 12345',
            'phone' => '081234567890',
            'label' => 'Rumah',
            'is_default' => true,
        ]);

        echo 'Test customer created with ID: ' . $customer->id . "\n";
    }
}
