<?php

namespace App\Http\Controllers\MultiDomain\Adapters;

use App\Http\Controllers\MultiDomain\Adapters\GeneralAdapterInterface;
use App\Http\Controllers\MultiDomain\Adapters\RequestAdapterInterface;
use App\Http\Controllers\MultiDomain\Adapters\ResponseAdapterInterface;

class AdapterDTO
{
    /**
     * The adapter data transfer object
     * for moving adapters from the
     * adapter builder.
     */
    public function __construct(
        public GeneralAdapterInterface $getOrPostAdapter,
        public RequestAdapterInterface $requestAdapter,
        public ResponseAdapterInterface $responseAdapter,
    ) {
    }
}
