<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * making an http request to the currency converter api
 */
class CurrencyConverter
{
    private $apiKey;
    protected $baseUrl = 'https://free.currconv.com/api/v7';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function convert($from, $to, $amount = 1)
    {
        $q = "{$from}_{$to}";
        $response = Http::baseUrl($this->baseUrl)
            ->get('/convert', [
                'q' => $q,
                'compact' => 'y',
                'apiKey' => $this->apiKey
            ]);

        $result = $response->json(); // convert response from json to array
        dd('put api token ya momen');
        return $result[$q]['val'] * $amount;

    }
}
