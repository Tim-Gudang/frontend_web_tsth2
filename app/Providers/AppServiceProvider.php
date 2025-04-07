<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
       View::composer('*', function ($view) {
        $token = Session::get('token');
        $user = null;

        if ($token) {
            $response = Http::withToken($token)->get('http://127.0.0.1:8090/api/users');

            if ($response->successful()) {
                $user = $response->json(); // Pastikan ini berisi array user
            }
        }

        $view->with('authUser', $user);
    });
    }
}
