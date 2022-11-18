<?php

namespace App\Http\Controllers\Payments;

use App\Models\Payment;
use App\Http\Controllers\Payments\Synchronizer\PaymentSyncRequestAdapterInterface;
use App\Http\Controllers\Payments\Synchronizer\PaymentSyncResponseAdapterInterface;
use App\Http\Controllers\MultiDomain\ResponseDecoder;
use App\Http\Controllers\RequestAdapters\GeneralRequestAdapterInterface;

class PaymentSynchronizer
{
    /**
     * DTOs are stored as an array property.
     *
     * @var array $DTOs
     */
    private array $DTOs;

    /**
     * The payment request adapter.
     *
     * @var PaymentSyncRequestAdapterInterface $paymentSyncRequestAdapter
     */
    private PaymentSyncRequestAdapterInterface $paymentSyncRequestAdapter;

    /**
     * The payment response adapter.
     *
     * @var PaymentSyncResponseAdapterInterface $paymentSyncResponseAdapter
     */
    private PaymentSyncResponseAdapterInterface $paymentSyncResponseAdapter;

    /**
     * The general get/post adapter.
     *
     * @var GeneralRequestAdapterInterface $GetOrPostAdapter
     */
    private GeneralRequestAdapterInterface $GetOrPostAdapter;

    /**
     * Builds the correct adapters for
     * the specified payment provider.
     *
     * @param string $paymentProvider
     * @return PaymentSynchronizer
     */
    public function selectAdapters(
        string $paymentProvider
    ): PaymentSynchronizer {

        // Build the request adaper
        $paymentSyncRequestAdapterClass =
            'App\Http\Controllers\Payments\Synchronizer'
            . '\PaymentSyncRequestAdapterFor'
            . strtoupper($paymentProvider);
        $this->paymentSyncRequestAdapter =
            new $paymentSyncRequestAdapterClass();

        // Build the response adaper
        $paymentSyncResponseAdapterClass =
            'App\Http\Controllers\Payments\Synchronizer'
            . '\PaymentSyncResponseAdapterFor'
            . strtoupper($paymentProvider);
        $this->paymentSyncResponseAdapter =
            new $paymentSyncResponseAdapterClass();

        // Build the general get/post adapter
        $requestAdapterPath = 'App\Http\Controllers\RequestAdapters';
        if (in_array(
            $paymentProvider,
            explode(',', env('USES_POST_TO_GET'))
        )) {
            $getOrPostAdapterClass = $requestAdapterPath
                . '\PostAdapterFor'
                . strtoupper($paymentProvider);
        } else {
            $getOrPostAdapterClass = $requestAdapterPath
                . '\GetAdapterFor'
                . strtoupper($paymentProvider);
        }
        $this->GetOrPostAdapter = new $getOrPostAdapterClass();

        return $this;
    }

    /**
     * Requests a response from the provider,
     * decodes it, and adapts it into DTOs.
     *
     * @param int $numberOfPaymentsToFetch
     * @param ResponseDecoder $responseDecoder
     * @return PaymentSynchronizer
     */
    public function requestPaymentsAndAdaptResponse(
        int $numberOfPaymentsToFetch,
        ResponseDecoder $responseDecoder
    ): PaymentSynchronizer {

        //Fetch the response
        $response =
            $this->paymentSyncRequestAdapter
                ->buildPostParameters(
                    numberOfPaymentsToFetch: $numberOfPaymentsToFetch
                )
                ->fetchResponse(
                    getOrPostAdapter: $this->GetOrPostAdapter
                );

        // Decode the response
        $responseBody =
            $responseDecoder->decode($response);

        // Adapt the response
        $this->DTOs = $this->paymentSyncResponseAdapter
            ->buildDTOsSyncAccountsReturnPayments(
                responseBody: $responseBody
            );
        return $this;
    }

    /**
     * Uses the DTOs to create payments for
     * any that do not already exist.
     *
     * @return void
     */
    public function createNewPayments(): void
    {
        foreach ($this->DTOs as $dto) {
            Payment::firstOrCreate(
                ['identifier' => $dto->identifier],
                [
                    'network' => $dto->network,
                    'amount' => $dto->amount,
                    'currency_id' => $dto->currency_id,
                    'originator_id' => $dto->originator_id,
                    'beneficiary_id' => $dto->beneficiary_id,
                    'memo' => $dto->memo,
                    'timestamp' => $dto->timestamp,
                ]
            );
        }

        return;
    }
}
