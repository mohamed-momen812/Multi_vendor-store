<?php

namespace App\Providers;

use App\Events\OrderEvent\OrderCreated;
use App\Listeners\DeductProductQuantity;
use App\Listeners\EmptyCart;
use App\Listeners\SendOrderCreateNotification;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
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

        // DeductProductQuantity should be first to update qauntity first before empty cart
        // this is the way to Laravel 11 register event with multiple listeners
        foreach ([DeductProductQuantity::class, SendOrderCreateNotification::class] as $listener) {
            Event::listen(OrderCreated::class, $listener);
        }



        // for make filter method in validator object with macros approch as a built in method
        Validator::extend('filter', function ($attribute, $value, $parameters, $validator) {
            return !in_array(strtolower($value), $parameters);
        }, 'The :attribute is prohipted');

        Paginator::useBootstrap(); // tell pagination that we use bootstrap
    }
}
