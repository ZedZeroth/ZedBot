<?php

namespace App\Http\Controllers\Payments;

use App\Models\Payment;
use App\Http\Livewire\PaymentFetcherComponent;

class PaymentSynchroniser
{
    /**
     * Fetches recent payments from external platforms
     * and inserts any new ones.
     *
     * @param string $provider
     * @param int $numberOfPayments
     * @return array
     */
    public function sync($provider, $numberOfPayments)
    {
        // Fetch the payment data
        $requestAdapterClass =
            'App\Http\Controllers\Payments\PaymentRequestAdapter'
            . $provider;

        $recentPaymentDTOs = (new PaymentFetcher())
            ->fetch(
                requestAdapter: new $requestAdapterClass(),
                numberOfPayments: $numberOfPayments,
            );

        foreach ($recentPaymentDTOs as $dto) {
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

        // Refresh view component payment count.
        (new PaymentFetcherComponent())->render();

        return $recentPaymentDTOs;
    }
}
