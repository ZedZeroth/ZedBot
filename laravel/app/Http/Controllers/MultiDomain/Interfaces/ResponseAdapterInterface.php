<?php

namespace App\Http\Controllers\MultiDomain\Adapters;

interface ResponseAdapterInterface
{
     /**
     * Builds an array of model DTOs
     * from the responseBody.
     *
     * @param array $responseBody
     * @return array
     */
    public function buildDTOs(
        array $responseBody
    ): array;
}
