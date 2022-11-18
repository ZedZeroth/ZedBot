<?php

namespace App\Http\Controllers\Payments\Synchronizer;

use App\Http\Controllers\RequestAdapters\GeneralRequestAdapterInterface;
use Illuminate\Http\Client\Response;

interface PaymentSyncRequestAdapterInterface
{
    /**
     * Build the post parameters.
     *
     * @param int $numberOfPaymentsToFetch
     * @return PaymentSyncRequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberOfPaymentsToFetch
    ): PaymentSyncRequestAdapterInterface;

    /**
     * Fetch the response.
     * 
     * @param GeneralRequestAdapterInterface $getOrPostAdapter
     * @return Response
     */
    public function fetchResponse(
        GeneralRequestAdapterInterface $getOrPostAdapter
    ): Response;
}
