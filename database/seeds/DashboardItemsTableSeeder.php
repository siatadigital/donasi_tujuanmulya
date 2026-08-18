<?php

use Illuminate\Database\Seeder;
use App\Models\DashboardItem;

class DashboardItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            'Grafik Area',
            'Grafik Akad',
            'Grafik Metode Pembayaran',
            'Grafik Total Transaksi',
        ];

        foreach ($items as $item) {
            DashboardItem::create([
                'name' => $item,
            ]);
        }
    }
}
