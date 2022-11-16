<?php

namespace App\Http\Controllers\Payments;

use App\Models\Payment;

class PaymentSynchroniser
{
    /**
     * DTOs are stored as an array property.
     *
     * @var array $DTOs
     */
    private array $DTOs;

    /**
     * The PaymentSynchroniser constructor.
     *
     * @param PaymentRequestAdapterInterface $paymentRequestAdapter
    */
    public function __construct(
        private PaymentRequestAdapterInterface $paymentRequestAdapter
    ) {
    }

    /**
     * Fetches recent payments from
     * an external provider.
     *
     * @return PaymentSynchroniser
     */
    public function fetchPayments(): PaymentSynchroniser
    {
        $this->DTOs = $this->paymentRequestAdapter->request();

        return $this;
    }

    /**
     * Uses the DTOs to create payments for
     * any that do not already exist.
     *
     * @return PaymentSynchroniser
     */
    public function createNewPayments(): PaymentSynchroniser
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

        return $this;
    }
}
