<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

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
        Response::macro('success', function ($message = 'Success', $data = [], $status = 200) {

            return Response::json([
                'status' => 'success',
                'message' => $message,
                'data' => $data,
            ], $status);
        });

        // Failure Response Macro
        Response::macro('failure', function ($message = 'Failure', $errors = [], $status = 400) {
            return Response::json([
                'status' => 'failure',
                'message' => $message,
                'errors' => $errors,
            ], $status);
        });
    }
}
