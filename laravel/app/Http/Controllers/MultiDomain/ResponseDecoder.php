<?php

namespace App\Http\Controllers\MultiDomain;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class ResponseDecoder
{
    /**
     * Decode an HTTP request response.
     * 
     * @param Response $response
     * @return array
     */
    public function decode(
        Response $response
    ): array
    {
        $statusCode = $response->status();
        $responseBody = json_decode(
            $response->getBody(),
            true
        );

        if ($statusCode == 200) {
            return $responseBody;
        } else {
            Log::error('Status code: ' . $statusCode);
            if (!empty($responseBody['responseStatus']['message'])) {
                Log::error($responseBody['responseStatus']['message']);
            }
            return [];
        }
    }
}
