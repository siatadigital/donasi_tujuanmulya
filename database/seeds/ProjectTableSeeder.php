<?php

use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->comment('seeding project...');
        factory(App\Models\Project::class, 'projectUser', 1)->create();
        factory(App\Models\Project::class, 10)->create();

        factory(App\Models\Project::class, 'pending', 20)->create();

        $this->command->comment('seeding reward...');
        $this->generateReward();
    }

    private function generateReward()
    {
        $project = App\Models\Project::find(1);
        App\Models\Reward::create([
            'project_id' => $project->id,
            'title' => 'Kaos',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat',
            'cover' => 'default.jpg',
            'price' => 100000,
        ]);

        App\Models\Reward::create([
            'project_id' => $project->id,
            'title' => 'Kaos dan Jam tangan',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat',
            'cover' => 'default.jpg',
            'price' => 300000,
        ]);

        App\Models\Reward::create([
            'project_id' => $project->id,
            'title' => 'Kaos jam tangan dvd',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat',
            'cover' => 'default.jpg',
            'price' => 500000,
        ]);

        App\Models\Reward::create([
            'project_id' => $project->id,
            'title' => 'Semua Reward dan mengikuti setiap kami latihan',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat',
            'cover' => 'default.jpg',
            'price' => 1000000,
        ]);
    }
}
