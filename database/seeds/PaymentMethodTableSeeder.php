<?php

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        // E-Wallet
        App\Models\PaymentMethod::create([
          'group_id' => 1,
          'code' => 'OVO',
          'logo' => 'ovo.png',
          'name' => 'OVO',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 1,
          'code' => 'JeniusPay',
          'logo' => 'jenius_pay.png',
          'name' => 'JeniusPay',
          'is_active_infak' => false,
          'is_active_zakat' => false,
          'is_active_campaign' => false,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 1,
          'code' => 'LinkAja',
          'logo' => 'link_aja.png',
          'name' => 'LinkAja',
          'is_active_infak' => false,
          'is_active_zakat' => false,
          'is_active_campaign' => false,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 1,
          'code' => 'gopay',
          'logo' => 'gopay.png',
          'name' => 'GoPay',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 1,
          'code' => 'DANA',
          'logo' => 'dana.png',
          'name' => 'DANA',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 1,
          'code' => 'SA',
          'logo' => 'shopee_pay.png',
          'name' => 'ShopeePay',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);

        // Virtual Account
        App\Models\PaymentMethod::create([
          'group_id' => 2,
          'code' => 'echannel',
          'logo' => 'bank_mandiri.png',
          'name' => 'VA Mandiri',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 2,
          'code' => 'other_va',
          'logo' => '',
          'name' => 'VA Universal',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);

        // Manual Transfer
        App\Models\PaymentMethod::create([
          'group_id' => 3,
          'code' => 'transfer_mandiri',
          'logo' => 'bank_mandiri.png',
          'name' => 'Bank Mandiri',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 3,
          'code' => 'transfer_bca',
          'logo' => 'bank_bca.png',
          'name' => 'BCA',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 3,
          'code' => 'transfer_bni_syariah',
          'logo' => 'bank_bni_syariah.png',
          'name' => 'BNI Syariah',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 3,
          'code' => 'transfer_bri_syariah',
          'logo' => 'bank_bri_syariah.png',
          'name' => 'BRI Syariah',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 3,
          'code' => 'transfer_bsm',
          'logo' => 'bank_bsm.png',
          'name' => 'Bank Mandiri Syariah (BSM)',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 3,
          'code' => 'transfer_mega_syariah',
          'logo' => 'bank_mega_syariah.png',
          'name' => 'Mega Syariah',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 3,
          'code' => 'transfer_muamalat',
          'logo' => 'bank_muamalat.png',
          'name' => 'Muamalat',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 3,
          'code' => 'transfer_cimb_niaga_syariah',
          'logo' => 'bank_cimb_niaga_syariah.png',
          'name' => 'CIMB Niaga Syariah',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
        App\Models\PaymentMethod::create([
          'group_id' => 3,
          'code' => 'transfer_permata_syariah',
          'logo' => 'bank_permata_syariah.png',
          'name' => 'Permata Syariah',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);

        App\Models\PaymentMethod::create([
          'group_id' => 2,
          'code' => 'va_bca',
          'logo' => 'bank_bca.png',
          'name' => 'VA BCA',
          'is_active_infak' => true,
          'is_active_zakat' => true,
          'is_active_campaign' => true,
        ]);
    }
}