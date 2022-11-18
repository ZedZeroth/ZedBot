<?php

namespace App\Http\Controllers\RequestAdapters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class GetAdapterForLCS implements
    GetAdapterInterface,
    GeneralAdapterInterface
{
    /**
     * Makes a GET request to the LCS API
     *
     * @param string $endpoint
     * @return Http
     */
    public function get(
        string $endpoint,
    ) {
        // Build the URL
        $url = env('ZED_LCS_DOMAIN')
            . env('ZED_LCS_PATH')
            . $endpoint;

        // Build the headers
        $headers = [
            'Authorization' => 'Token '
                . DB::table('keys')
                    ->where('service', 'LCS')
                    ->first()->key,
            'Content-Type' => 'application/json'
        ];

        // Execute the request and return the response
        return Http::withHeaders($headers)
            ->connectTimeout(10)
            ->retry(3, 100)
            ->get($url);
    }
}
