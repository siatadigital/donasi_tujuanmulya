<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DashboardItem;
use App\Models\UserDashboardPrivilege;

class UserDashboardPrivilegesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        $dashboardItems = DashboardItem::all();

        foreach ($dashboardItems as $dashboardItem) {
            UserDashboardPrivilege::create([
                'user_id' => $user->id,
                'dashboard_item_id' => $dashboardItem->id,
                'can_access' => 1,
            ]);
        }
    }
}
