<?php

namespace App\Http\Controllers\Payments;

use App\Models\Payment;
use App\Http\Controllers\Payments\Synchronizer\PaymentSyncRequestAdapterInterface;
use App\Http\Controllers\Payments\Synchronizer\PaymentSyncResponseAdapterInterface;
use App\Http\Controllers\MultiDomain\ResponseDecoder;
use App\Http\Controllers\MultiDomain\AdapterDTO;
use App\Http\Controllers\RequestAdapters\GeneralRequestAdapterInterface;

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
