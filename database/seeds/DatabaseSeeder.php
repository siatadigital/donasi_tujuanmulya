<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // truncate all tables first
        DB::table('users')->truncate();
        DB::table('options')->truncate();
        DB::table('projects')->truncate();
        DB::table('rewards')->truncate();
        DB::table('supporters')->truncate();
        DB::table('user_favorites')->truncate();
        DB::table('comments')->truncate();
        DB::table('media')->truncate();
        DB::table('events')->truncate();
        DB::table('category')->truncate();
        DB::table('group_privileges')->truncate();
        DB::table('user_privileges')->truncate();
        DB::table('group_privilege_details')->truncate();
        DB::table('menu_admins')->truncate();
        DB::table('dashboard_items')->truncate();
        DB::table('user_dashboard_privileges')->truncate();

        // register seeder
        $this->call(OptionTableSeeder::class);
        $this->call(PaymentMethodGroupTableSeeder::class);
        $this->call(PaymentMethodTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(EventTableSeeder::class);
        $this->call(BlogTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(ProjectTableSeeder::class);
        $this->call(MenuAdminsTableSeeder::class);
        $this->call(GroupPrivilegesTableSeeder::class);
        $this->call(UserPrivilegesTableSeeder::class);
        $this->call(GroupPrivilegeDetailsTableSeeder::class);
        $this->call(DashboardItemsTableSeeder::class);
        $this->call(UserDashboardPrivilegesTableSeeder::class);
        // $this->call(CommentTableSeeder::class); //related with blog

        $provinceSqlPath = 'provinsi.sql';
        $citySqlPath = 'kota.sql';

        DB::unprepared(file_get_contents($provinceSqlPath));
        $this->command->info('Provinsi table seeded!');

        DB::unprepared(file_get_contents($citySqlPath));
        $this->command->info('Kota table seeded!');

        Model::reguard();
    }
}
