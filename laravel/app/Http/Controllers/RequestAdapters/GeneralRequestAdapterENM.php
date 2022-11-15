<?php

namespace App\Http\Controllers\RequestAdapters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class GeneralRequestAdapterENM implements GeneralRequestAdapterInterface
{
    /**
     * Makes requests to the ENM API
     *
     * @param string $endpoint
     * @param array $postParameters
     * @return Http
     */
    public function request(
        string $endpoint,
        array $postParameters
    ) {
        $url = env('ZED_ENM_DOMAIN')
            . env('ZED_ENM_PATH')
            . $endpoint;

        $headers = [
            'Authorization' => 'Bearer '
                . DB::table('keys')
                    ->where('service', 'ENM')
                    ->first()->key
        ];

        return Http::withHeaders($headers)
            ->timeout(30)->post($url, $postParameters);
    }
}
