<?php

namespace App\Providers;

use App\Http\View\CategoryComposer;
use App\Http\View\KeycapComposer;
use App\Http\View\KeySwitchComposer;
use App\Http\View\MerkComposer;
use App\Http\View\SizeComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.ecommerce.nav.navbar', CategoryComposer::class);
        View::composer('ecommerce.partials.sidebar-kebs', MerkComposer::class);
        View::composer('ecommerce.partials.sidebar-kebs-size', SizeComposer::class);
        View::composer('ecommerce.partials.sidebar-kebs-switch', KeySwitchComposer::class);
        View::composer('ecommerce.partials.sidebar-keycap-type', KeycapComposer::class);
        Paginator::defaultView('pagination::default');
    }
}
