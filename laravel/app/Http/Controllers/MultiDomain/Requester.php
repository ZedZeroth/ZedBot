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
     * @return array
     */
    public function request(
        AdapterDTO $adapterDTO,
        int $numberToFetch,
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

        /*💬*/ //var_dump($response);

        // Decode the response
        $statusCode = $response->status();
        $responseBody = json_decode(
            $response->getBody(),
            true
        );

        /*💬*/ //print_r($responseBody);

        //Adapt a valid response and return the DTOs
        if ($statusCode == 200) {
            return $adapterDTO->responseAdapter
                ->buildDTOs(
                    responseBody: $responseBody
                );

        // Throw an exception for an invalid response
        } else {
            /*💬*/ //print_r($responseBody);
            /*
            if (!empty($responseBody['responseStatus']['message'])) {
                throw new Exception($responseBody['responseStatus']['message'], $statusCode);
            } else {
                throw new Exception('$responseBody[responseStatus][message] is empty!', $statusCode);
            }
            */
            return [];
        }
    }
}
