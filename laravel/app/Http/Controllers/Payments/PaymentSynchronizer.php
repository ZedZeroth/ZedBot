<?php

namespace App\Http\Controllers\Payments;

use App\Models\Payment;
use App\Http\Controllers\Payments\Synchronizer\PaymentSyncRequestAdapterInterface;
use App\Http\Controllers\Payments\Synchronizer\PaymentSyncResponseAdapterInterface;

class PaymentSynchronizer
{
    /**
     * DTOs are stored as an array property.
     *
     * @var array $DTOs
     */
    private array $DTOs;

    /**
     * The response returned by the request.
     *
     * @var array $responseBody
     */
    private array $responseBody;

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

        return $this;
    }

    /**
     * Requests a response from the provider
     * and returns the reponseBody array.
     *
     * @param int $numberOfPaymentsToFetch
     * @return PaymentSynchronizer
     */
    public function requestPayments(
        int $numberOfPaymentsToFetch
    ): PaymentSynchronizer {
        $this->responseBody =
            $this->paymentSyncRequestAdapter
                ->setNumberOfPaymentsToFetch(numberOfPaymentsToFetch: $numberOfPaymentsToFetch)
                ->buildPostParameters()
                ->fetchResponse()
                ->parseResponse()
                ->returnResponseBodyArray();
        return $this;
    }

    /**
     * Adapts the responseBody array into
     * an array of DTOs.
     *
     * @return PaymentSynchronizer
     */
    public function adaptResponse(): PaymentSynchronizer
    {
        $this->DTOs = $this->paymentSyncResponseAdapter
            ->setResponseBody(responseBody: $this->responseBody)
            ->buildAccountDTOs()
            ->syncAccountDTOs()
            ->buildPaymentDTOs()
            ->returnPaymentDTOs();
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
