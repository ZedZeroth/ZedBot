<?php

namespace App\Http\Controllers\RequestAdapters;

use Illuminate\Support\Facades\Http;

interface PostAdapterInterface
{
    /**
     * Makes a POST request to an API
     *
     * @param string $endpoint
     * @param array $postParameters
     * @return Http
     */
    public function post(
        string $endpoint,
        array $postParameters
    );
}
