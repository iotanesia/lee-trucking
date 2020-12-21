<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use DB;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            if(Auth::check()) {
                $schema = Auth::user()->schema.'.';
                $menus = DB::table($schema.'usr_group_menu')
                         ->join($schema.'usr_menu', 'usr_group_menu.menu_id', 'usr_menu.id')
                         ->orderBy('sort', 'ASC')
                         ->where('group_id', Auth::user()->group_id)->get();

                View::share('menus', $menus);
            }
          });
      
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
