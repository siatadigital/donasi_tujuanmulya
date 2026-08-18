<?php

use App\Models\PaymentMethodGroup;
use Illuminate\Database\Seeder;

class PaymentMethodGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        App\Models\PaymentMethodGroup::create([
            'name' => 'E-Wallet',
            'is_active' => true,
        ]);
        App\Models\PaymentMethodGroup::create([
            'name' => 'Virtual Account',
            'is_active' => true,
        ]);
        App\Models\PaymentMethodGroup::create([
            'name' => 'Manual Transfer',
            'is_active' => true,
        ]);
    }
}