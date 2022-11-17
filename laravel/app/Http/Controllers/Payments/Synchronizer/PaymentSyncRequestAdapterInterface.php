<?php

namespace App\Http\Controllers\Payments\Synchronizer;

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
     * @return Response
     */
    public function fetchResponse(): Response;
}
