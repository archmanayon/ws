<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

      
        Blade::if('admin', function () {
          
            return auth()->user()->username == 'abmanayon'?
                    request()->user()->cannot('admin'):
                    request()->user()->can('admin');
                    // pwede pud
                    // auth()->user()->cannot('admin'):
                    // auth()->user()->can('admin');
        });

        Blade::if('head', function () {
          
            return  auth()->user()->role_id == 4 ?
                    request()->user()->cannot('head'):
                    request()->user()->can('head');
        });

        Blade::if('staffhead', function () {
          
            return  auth()->user()->role_id == 5 ?
                    request()->user()->cannot('staffhead'):
                    request()->user()->can('staffhead');
        });

        Blade::if('staff', function () {
          
            return  auth()->user()->role_id == 2?
                    request()->user()->cannot('staff'):
                    request()->user()->can('staff');
        });
        
        Blade::if('ws', function () {
          
            return  auth()->user()->role_id == 2 ||
                    auth()->user()->role_id == 1?
                    request()->user()->cannot('ws'):
                    request()->user()->can('ws');
        });

    }
}
