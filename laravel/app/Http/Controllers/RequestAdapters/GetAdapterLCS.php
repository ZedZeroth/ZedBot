<?php

namespace App\Http\Controllers\RequestAdapters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class GetAdapterLCS implements GetAdapterInterface
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
        $url = env('ZED_LCS_DOMAIN')
            . env('ZED_LCS_PATH')
            . $endpoint;

        $headers = [
            'Authorization' => 'Token '
                . DB::table('keys')
                    ->where('service', 'LCS')
                    ->first()->key,
            'Content-Type' => 'application/json'
        ];

        return Http::withHeaders($headers)
            ->connectTimeout(30)
            ->retry(3, 100)
            ->get($url);
    }
}
