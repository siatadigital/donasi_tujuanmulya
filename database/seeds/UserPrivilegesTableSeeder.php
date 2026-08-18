<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MenuAdmin;
use App\Models\UserPrivilege;

class UserPrivilegesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        $menuAdmins = MenuAdmin::all();

        // Set Master as group privilege admin has
        $user->update(['group_privilege_id' => 1]);

        foreach ($menuAdmins as $menuAdmin) {
            UserPrivilege::create([
                'user_id' => $user->id,
                'menu_admin_id' => $menuAdmin->id,
                'can_access' => 1,
            ]);
        }
    }
}
