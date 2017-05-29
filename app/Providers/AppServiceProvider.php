<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        //https://laracasts.com/series/laravel-from-scratch-2017/episodes/21?autoplay=true
        //view composer
        //for every sidebar, add a callback function. when load a sidebar, we load these..
        view()->composer('layouts.sidebar', function ($view) {
            $view->with('archives', \App\Vote::archives());
            //$view->with('tags', \App\Tag::has('posts')->pluck('name'));

            /*$archives = \App\Post::archives();
            $tags = \App\Tag::has('posts')->pluck('name');
            $view->with(compact('archives', 'tags'));*/
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
