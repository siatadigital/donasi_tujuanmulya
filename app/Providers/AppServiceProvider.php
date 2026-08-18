<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Option;
use App\Models\Project;
use App\Models\Supporter;
use App\Models\Donation;
use App\Models\Zakat;
use App\Models\User;
use App\Repositories\Base\OptionData;
use App\Repositories\Blog\BlogRepository;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	view()->addNamespace('admin', base_path('resources/viewsAdmin'));
    	
        require app_path('Http/viewComposers.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!$this->app->runningInConsole()) {
            $this->app->singleton('OptionData', function ($app) {
                return new OptionData(new Option);
            });
        }

        $this->app->bind('UserRepository', function ($app) {
            return new UserRepository(new User);
        });

        $this->app->bind('ProjectRepository', function ($app) {
            return new ProjectRepository(new Project);
        });

        $this->app->bind('BlogRepository', function ($app) {
            return new BlogRepository(new Blog);
        });

        // view()->composer('*',function($view){
        //     $tickerList = array();
        //     $supporter = Supporter::where('status', 'accept')->take(3)->get();
        //     $donation = Donation::where('status', 'success')->take(3)->get();
        //     $zakat = Donation::where('status', 'zakat')->take(3)->get();
        //     foreach ($supporter as $item) {
        //         $tickerList[] = array(
        //             'fullname' => $item->fullname,
        //             'description' => 'Telah memberikan Infak Terikat',
        //             'date' => $item->created_at,
        //         );
        //     }
        //     foreach ($donation as $item) {
        //         $tickerList[] = array(
        //             'fullname' => $item->fullname,
        //             'description' => 'Telah memberikan Infak Umum',
        //             'date' => $item->created_at,
        //         );
        //     }
        //     foreach ($zakat as $item) {
        //         $tickerList[] = array(
        //             'fullname' => $item->fullname,
        //             'description' => 'Telah memberikan Zakat'.$item->type,
        //             'date' => $item->created_at,
        //         );
        //     }

        //     view()->share('tickerList', $tickerList);
        // });
    }
}
