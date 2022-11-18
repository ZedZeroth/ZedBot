<?php

namespace App\Http\Controllers\Payments;

use App\Models\Payment;

class PaymentSynchronizer
{
    /**
     * Uses the DTOs to create payments for
     * any that do not already exist.
     *
     * @param array $DTOs
     */
    public function createNewPayments(
        array $DTOs
    ): void {
        foreach ($DTOs as $dto) {
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
