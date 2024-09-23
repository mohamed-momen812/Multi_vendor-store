<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\CurrencyConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CurrencyConverterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|string|size:3',
        ]);

        $baseCurrencyCode = config('app.currency'); // from
        $currencyCode = $request->input('currency_code'); // to

        // check if the currencyCode is exist in cache
        $cacheKey = 'currency_rate_' . $currencyCode; // make cachekey for each currency

        $rate = Cache::get($cacheKey, 0);

        if (!$rate) {
            // accesst the currency.converter from service container
            $converter = app('currency.converter');
            $rate = $converter->convert($baseCurrencyCode, $currencyCode);

            Cache::put($cacheKey, $rate, now()->addMinutes(60));
        }

        // put user currency_code in the session for user to make it selected
        Session::put('currency_code', $currencyCode);

        return redirect()->back();

    }
}
