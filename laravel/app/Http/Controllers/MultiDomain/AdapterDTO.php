<?php

namespace App\Http\Controllers\MultiDomain;

use App\Http\Controllers\RequestAdapters\GeneralAdapterInterface;

class AdapterDTO
{
    /**
     * The adapter data transfer object
     * for moving adapters from the
     * adapter builder.
     */
    public function __construct(
        public GeneralAdapterInterface $getOrPostAdapter,
        public GeneralAdapterInterface $requestAdapter,
        public GeneralAdapterInterface $responseAdapter,
    ) {
    }
}
