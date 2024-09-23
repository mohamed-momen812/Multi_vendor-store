<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\CartServiceProvider::class,
    App\Providers\FortifyServiceProvider::class, //adding fortify servicr provider to app providers
    Bezhanov\Faker\Laravel\FakerServiceProvider::class,
];
