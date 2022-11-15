<?php

namespace App\Http\Controllers\RequestAdapters;

use Illuminate\Support\Facades\Http;

interface GetAdapterInterface
{
    /**
     * Makes a GET request to an API
     *
     * @param string $endpoint
     * @return Http
     */
    public function get(
        string $endpoint,
    );
}
