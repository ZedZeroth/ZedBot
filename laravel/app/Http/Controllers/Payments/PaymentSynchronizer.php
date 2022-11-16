<?php

namespace App\Http\Controllers\Payments;

use App\Models\Payment;

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
     * @var PaymentRequestAdapterInterface $paymentRequestAdapter
     */
    private PaymentRequestAdapterInterface $paymentRequestAdapter;

    /**
     * The payment response adapter.
     *
     * @var PaymentResponseAdapterInterface $paymentResponseAdapter
     */
    private PaymentResponseAdapterInterface $paymentResponseAdapter;

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
        $paymentRequestAdapterClass =
            'App\Http\Controllers\Payments\PaymentRequestAdapter'
            . strtoupper($paymentProvider);
        $this->paymentRequestAdapter = new $paymentRequestAdapterClass();

        // Build the response adaper
        $paymentResponseAdapterClass =
            'App\Http\Controllers\Payments\PaymentResponseAdapter'
            . strtoupper($paymentProvider);
        $this->paymentResponseAdapter = new $paymentResponseAdapterClass();

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
            $this->paymentRequestAdapter
                ->request($numberOfPaymentsToFetch);
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
        $this->DTOs = $this->paymentResponseAdapter
            ->adapt(responseBody: $this->responseBody);
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
