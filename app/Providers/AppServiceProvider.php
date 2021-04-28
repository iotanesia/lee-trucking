<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use DB;
use View;
use Validator;

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
                $all['user_detail'] = DB::table($schema.'usr_detail')->where('id_user', Auth::user()->id)->first();
                // dd($all);
                $all['menus'] = DB::table($schema.'usr_group_menu')
                         ->join($schema.'usr_menu', 'usr_group_menu.menu_id', 'usr_menu.id')
                         ->orderBy('sort', 'ASC')
                         ->where('group_id', Auth::user()->group_id)->get();

                $all['notif'] = DB::table($schema.'notification')->where(function($query){
                    $query->where('id_user_to', Auth::user()->id)
                          ->orWhere('id_group', Auth::user()->group_id);
                })->where('is_read', 'f')->limit(6);

                View::share('all', $all);
            }


          });


          Validator::extend('iunique', function ($attribute, $value, $parameters, $validator) {
            // dd($attribute, $value, $parameters, $validator);
            $count = DB::table($parameters[0])
                     ->where($attribute, $value)
                     ->where('is_deleted', false);

            if(!$count->count()) {
                return true;
                
            } else {
                $validator->addReplacer('iunique', function($message) use($attribute) {
                    return str_replace('validation.iunique', 'The '. ucfirst($attribute).' has already been taken', $message);
                });
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
