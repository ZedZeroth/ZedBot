<?php

namespace App\Http\Controllers\MultiDomain;

class Requester
{
    /**
     * Makes a request (via specific request adapters),
     * adapts the response (via specific response adapters)
     * returning the relevant DTOs.
     *
     * @param AdapterDTO $adapterDTO
     * @param int $numberToFetch
     * @param ResponseDecoder $responseDecoder
     * @return array
     */
    public function request(
        AdapterDTO $adapterDTO,
        int $numberToFetch,
        ResponseDecoder $responseDecoder
    ): array {

        //Fetch the response
        $response =
            $adapterDTO->requestAdapter
                ->buildPostParameters(
                    numberToFetch: $numberToFetch
                )
                ->fetchResponse(
                    getOrPostAdapter: $adapterDTO->getOrPostAdapter
                );

        // Decode the response
        $responseBody =
            $responseDecoder->decode($response);

        // Adapt the response and return the DTOs
        return $adapterDTO->responseAdapter
            ->buildDTOs(
                responseBody: $responseBody
            );
    }
}
