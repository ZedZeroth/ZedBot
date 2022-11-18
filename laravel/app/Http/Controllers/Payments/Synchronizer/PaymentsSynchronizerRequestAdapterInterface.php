<?php

namespace App\Http\Controllers\Payments\Synchronizer;

use App\Http\Controllers\RequestAdapters\GeneralAdapterInterface;
use Illuminate\Http\Client\Response;

interface PaymentsSynchronizerRequestAdapterInterface
{
    /**
     * Build the post parameters.
     *
     * @param int $numberToFetch
     * @return PaymentsSynchronizerRequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberToFetch
    ): PaymentsSynchronizerRequestAdapterInterface;

    /**
     * Fetch the response.
     * 
     * @param GeneralAdapterInterface $getOrPostAdapter
     * @return Response
     */
    public function fetchResponse(
        GeneralAdapterInterface $getOrPostAdapter
    ): Response;
}
