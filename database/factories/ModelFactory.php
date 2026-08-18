<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
 */

// for user
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    $name = $faker->name;
    return [
        'name' => $name,
        'username' => \Illuminate\Support\Str::slug($name),
        'email' => $faker->email,
        'password' => bcrypt('dummy123'),
        'avatar' => 'default.jpg',
        'cover' => 'default.jpg',
        'is_artist' => $faker->randomElement([0, 1]),
        'province' => $faker->state,
        'city' => $faker->city,
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'bio' => $faker->paragraph(3),
        'quotes' => $faker->paragraph(1),
    ];
});

// user admin
$factory->defineAs(App\Models\User::class, 'admin', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\Models\User::class);

    return array_merge($user, [
        'name' => 'Admin Tampan',
        'username' => 'admin',
        'email' => 'admin@gmail.com',
        'is_superadmin' => 1,
    ]);
});

// for project
$factory->define(App\Models\Project::class, function (Faker\Generator $faker) {
    // get artist
    $artist = App\Models\User::where('is_artist', 1)->orderBy(DB::raw('RAND()'))->first();

    $title = $faker->sentence($nbWords = 10);
    return [
        'user_id' => $artist->id,
        'title' => $title,
        'slug' => Illuminate\Support\Str::slug($title),
        'summary' => $faker->paragraph(3),
        'content' => '<p>' . implode('</p><p>', $faker->paragraphs(15)) . '</p>',
        'cover' => 'default.jpg',
        'money_target' => $faker->randomElement([5000000, 6000000, 7000000, 8000000, 9000000]),
        'category_id'  => $faker->randomElement([1,2]),
        // 'money_progress' => $faker->randomElement([1000000, 2000000, 3000000, 4000000, 4500000]),
        'time_start' => \Carbon\Carbon::now()->addWeeks($faker->randomElement([1, 4])),
        'time_end' => \Carbon\Carbon::now()->addWeeks($faker->randomElement([5, 8])),
        'status' => 'active',
        'created_at' => \Carbon\Carbon::now()->addDays($faker->randomElement([1, 3, 5])),
        'updated_at' => \Carbon\Carbon::now()->addDays($faker->randomElement([1, 3, 5])),
    ];
});

$factory->defineAs(App\Models\Project::class, 'projectUser', function (Faker\Generator $faker) use ($factory) {
    $selectedUser = App\Models\User::find(1);
    $project = $factory->raw(App\Models\Project::class);

    return array_merge($project, [
        'user_id' => $selectedUser->id,
    ]);
});

// for project pending
$factory->defineAs(App\Models\Project::class, 'pending', function (Faker\Generator $faker) use ($factory) {
    $project = $factory->raw(App\Models\Project::class);

    return array_merge($project, ['status' => 'pending']);
});

// for reward
$factory->define(App\Models\Reward::class, function (Faker\Generator $faker) {
    // get project only active
    $project = App\Models\Project::where('status', 'active')->orderBy(DB::raw('RAND()'))->first();
    // $price = 100000;

    return [
        'project_id' => $project->id,
        'title' => $faker->paragraph(1),
        'content' => '<p>' . implode('</p><p>', $faker->paragraphs(15)) . '</p>',
        'cover' => 'default.jpg',
        'price' => (integer) $faker->unique()->randomDigitNotNull . '00000',
    ];
});

// for supporter
$factory->define(App\Models\Supporter::class, function (Faker\Generator $faker) {
    // get user only
    $user = App\Models\User::where('is_artist', 0)->orderBy(DB::raw('RAND()'))->first();

    // get project only active
    $project = App\Models\Project::where('status', 'active')->find(1);

    $reward = $project->rewards()->orderBy(DB::raw('RAND()'))->first();

    $title = $faker->paragraph(2);
    return [
        'user_id' => $user->id,
        'project_id' => $project->id,
        'reward_id' => $reward->id,
        'money' => $reward->price,
        'payment_method' => $faker->randomElement(['bni', 'bri', 'bca']),
        'notes' => '<p>' . implode('</p><p>', $faker->paragraphs(15)) . '</p>',
        'status' => $faker->randomElement(['accept', 'pending']),
    ];
});

// for Comment
$factory->define(App\Models\Comment::class, function (Faker\Generator $faker) {
    // get all user
    // $user = App\Models\User::orderBy(DB::raw('RAND()'))->first();
    // // get active project
    // $project = App\Models\Project::where('status', 'active')->orderBy(DB::raw('RAND()'))->first();
    // // get publish blog
    // // $blog = App\Models\Blog::where('status', 'publish')->orderBy(DB::raw('RAND()'))->first();

    // return [
    //     'user_id' => $user->id,
    //     'commentable_id' => $faker->randomElement([$project->id, $blog->id]),
    //     'commentable_type' => $faker->randomElement(['project', 'blog']),
    //     'content' => $faker->paragraph(3),
    //     'status' => $faker->randomElement(['publish', 'pending']),
    // ];
});

// for Event
$factory->define(App\Models\Event::class, function (Faker\Generator $faker) {
    // get artist only
    $artist = App\Models\User::where('is_artist', 1)->orderBy(DB::raw('RAND()'))->first();

    $title = $faker->paragraph(1);

    return [
        'user_id' => $artist->id,
        'title' => $title,
        'slug' => Illuminate\Support\Str::slug($title),
        'cover' => $faker->randomElement(['default.jpg','default.jpg']),
        'schedule' => \Carbon\Carbon::now()->addWeeks($faker->randomElement([1, 4])),
        'location' => $faker->randomElement([
            'Simomulyo, East Java, Indonesia',
            'Banyu Urip Kidul I A, Banyu Urip, East Java, Indonesia',
            'Pohon Kopi, Jalan Nginden Semolo, Ngiden Jangkungan, East Java, Indonesia',
        ]),
        'place_id' => '',
        'htm' => $faker->randomElement([300000, 600000, 1000000]),
        'description' => $faker->paragraph(1),
        'lng' => $faker->randomElement(['112.7058706', '112.7631983']),
        'lat' => $faker->randomElement(['-7.2712904', '-7.2849277']),
    ];
});

// for Blog
$factory->define(App\Models\Blog::class, function (Faker\Generator $faker) {
    // get superadmin only
    $superadmin = App\Models\User::where('is_superadmin', 1)->orderBy(DB::raw('RAND()'))->first();

    $title = $faker->paragraph(1);

    return [
        'user_id' => $superadmin->id,
        'title' => $title,
        'slug' => Illuminate\Support\Str::slug($title),
        'description' => $faker->paragraph(1),
        'content' => $faker->paragraph(25),
        'cover' => $faker->randomElement(['default.jpg','default.jpg']),
    ];
});
