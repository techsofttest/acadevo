<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View; 

use App\Models\Category;

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

        

        View::composer(
            ['layouts.partials.header', 'layouts.partials.footer'],
            function ($view) {
                $view->with([
                    'header_categories' => Category::whereNull('parent_id')
        ->where('is_active', true)
        ->select('id', 'name', 'slug','image')
        ->with([
            'children:id,name,slug,parent_id'
        ])
        ->orderBy('name')
        ->get(),
                    'user'       => auth()->user(),
                    'cartCount'  => session('cart_count', 0),
                ]);
            }
        );

    }
}
