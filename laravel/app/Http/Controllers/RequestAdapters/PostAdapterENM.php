<?php

namespace App\Http\Controllers\RequestAdapters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PostAdapterENM implements PostAdapterInterface
{
    /**
     * Makes a POST request to the ENM API
     *
     * @param string $endpoint
     * @param array $postParameters
     * @return Http
     */
    public function post(
        string $endpoint,
        array $postParameters
    ) {
        // Build the URL
        $url = env('ZED_ENM_DOMAIN')
            . env('ZED_ENM_PATH')
            . $endpoint;

        // Build the headers
        $headers = [
            'Authorization' => 'Bearer '
                . DB::table('keys')
                    ->where('service', 'ENM')
                    ->first()->key
        ];

        // Execute the request and return the response
        return Http::withHeaders($headers)
            ->connectTimeout(30)
            ->retry(3, 100)
            ->post($url, $postParameters);
    }
}
