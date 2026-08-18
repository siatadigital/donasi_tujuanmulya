<?php

use Illuminate\Database\Seeder;
use App\Models\GroupPrivilege;

class GroupPrivilegesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GroupPrivilege::create([
            'title' => 'Master',
            'description' => 'Akses semua menu',
        ]);

        GroupPrivilege::create([
            'title' => 'Main Menu',
            'description' => 'Akses hanya menu utama',
        ]);

        GroupPrivilege::create([
            'title' => 'Transaction',
            'description' => 'Akses hanya menu bagian transaction',
        ]);
    }
}
