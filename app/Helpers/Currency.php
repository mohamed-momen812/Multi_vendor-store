<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use NumberFormatter;


// has aliase from config.app = Currency to use in view
class Currency
{
    public static function format($amount, $currency = null) {

        $formatter = new NumberFormatter(config("app.locale"), style: NumberFormatter::CURRENCY);

        $baseCurrency = config('app.currency', 'USD');

        if($currency === null) {
            // access currency_code from session if exists
            $currency = Session::get('currency_code', $baseCurrency);
        }

        if($currency != $baseCurrency) {
            $rate = Cache::get('currency_rate_' . $currency, 1);
            $amount *= $rate;
        }
        return $formatter->formatCurrency($amount, $currency);
    }
}
