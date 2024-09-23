<?php

namespace App\Providers;

use App\Events\OrderEvent\OrderCreated;
use App\Http\Controllers\Api\AccessTokenController;
use App\Listeners\DeductProductQuantity;
use App\Listeners\EmptyCart;
use App\Listeners\SendOrderCreateNotification;
use App\Services\CurrencyConverter;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // access new CurrencyConverter(config('services.currency_converter.api_key')) just via app('currency.converter')
        $this->app->bind('currency.converter', function () {
            return new CurrencyConverter(config('services.currency_converter.api_key'));
        });
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {

        JsonResource::withoutWrapping(); // to stop using data wrapping in the resource responce

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
