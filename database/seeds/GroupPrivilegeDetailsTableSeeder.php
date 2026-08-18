<?php

use Illuminate\Database\Seeder;
use App\Models\GroupPrivilege;
use App\Models\GroupPrivilegeDetail;
use App\Models\MenuAdmin;

class GroupPrivilegeDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupPrivileges = GroupPrivilege::all();
        $menuAdmins = MenuAdmin::all();
        
        // Master
        foreach ($menuAdmins as $menuAdmin) {
            GroupPrivilegeDetail::create([
                'group_privilege_id' => 1,
                'menu_admin_id' => $menuAdmin->id,
            ]);
        }

        // Main Menu
        foreach ($menuAdmins->slice(0, 47) as $menuAdmin) {
            GroupPrivilegeDetail::create([
                'group_privilege_id' => 2,
                'menu_admin_id' => $menuAdmin->id,
            ]);
        }

        // Transaction
        foreach ($menuAdmins->slice(47) as $menuAdmin) {
            GroupPrivilegeDetail::create([
                'group_privilege_id' => 3,
                'menu_admin_id' => $menuAdmin->id,
            ]);
        }
    }
}
