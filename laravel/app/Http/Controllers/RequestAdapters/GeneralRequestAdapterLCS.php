<?php

namespace App\Http\Controllers\RequestAdapters;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class GeneralRequestAdapterLCS implements GeneralRequestAdapterInterface
{
    /**
     * Makes requests to the LCS API
     *
     * @param string $endpoint
     * @param array $postParameters
     * @return Http
     */
    public function request(
        string $endpoint,
        array $postParameters
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
            ->timeout(30)->get($url);
    }
}
