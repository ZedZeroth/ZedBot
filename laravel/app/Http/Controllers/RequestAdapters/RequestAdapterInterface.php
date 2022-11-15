<?php

namespace App\Http\Controllers\RequestAdapters;

use Illuminate\Support\Facades\Http;

interface RequestAdapterInterface
{
    /**
     * Makes requests to an API
     *
     * @param string $endpoint
     * @param array $postParameters
     * @return Http
     */
    public function request(
        string $endpoint,
        array $postParameters
    );
}
