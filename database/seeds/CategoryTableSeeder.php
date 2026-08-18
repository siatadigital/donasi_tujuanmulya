<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        App\Models\Category::create([
            'category_name' => 'Buku',
            'category_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
        ]);

        App\Models\Category::create([
            'category_name' => 'Makanan',
            'category_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
        ]);

        App\Models\Category::create([
            'category_name' => 'Musik',
            'category_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
        ]);

        App\Models\Category::create([
            'category_name' => 'Seni',
            'category_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
        ]);

        App\Models\Category::create([
            'category_name' => 'Film dan Video',
            'category_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
        ]);

        App\Models\Category::create([
            'category_name' => 'Design',
            'category_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
        ]);

        App\Models\Category::create([
            'category_name' => 'Karya Jurnalistik',
            'category_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
        ]);

        App\Models\Category::create([
            'category_name' => 'Teknologi',
            'category_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
        ]);

        App\Models\Category::create([
            'category_name' => 'Fashion',
            'category_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,',
        ]);
    }
}