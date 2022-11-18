<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

use Illuminate\Http\Client\Response;
use App\Http\Controllers\RequestAdapters\GeneralAdapterInterface;

interface AccountsSynchronizerRequestAdapterInterface
{
    /**
     * Build the post parameters.
     *
     * @param int $numberToFetch
     * @return AccountsSynchronizerRequestAdapterInterface
     */
    public function buildPostParameters(
        int $numberToFetch
        ): AccountsSynchronizerRequestAdapterInterface;

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
