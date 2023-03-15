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
            return auth()->user()->username == 'bitin112'?
                    request()->user()->cannot('admin'):
                    request()->user()->can('admin');
                    // pwede pud
                    // auth()->user()->cannot('admin'):
                    // auth()->user()->can('admin');
        });
        

    }
}
