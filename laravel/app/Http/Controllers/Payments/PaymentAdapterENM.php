<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Account;
use App\Models\Currency;
use App\Http\Controllers\RequestAdapters\RequestAdapterENM;

class PaymentAdapterENM implements PaymentAdapterInterface
{
    /**
     * Requests transacations (payments) from ENM.
     *
     * @param int $numberOfPayments
     * @return Http
     */
    public function adaptRequest($numberOfPayments)
    {
        $postParameters = [
            'accountCode' => env('ZED_ENM_ACCOUNT_CODE'),
            'take' => $numberOfPayments,
        ];

        return (new RequestAdapterENM())
            ->request(
                endpoint: env('ZED_ENM_TRANSACTIONS_ENDPOINT'),
                postParameters: $postParameters,
            );
    }

    /**
     * Converts an ENM request response into
     * an array of DTOs for the controller.
     *
     * @param array $responseBody
     * @return array
     */
    public function adaptResponse($responseBody)
    {
        $DTOs = [];
        foreach ($responseBody['results'] as $result) {
            /*💬*/ //print_r($result);

            try {
                // Find currency and convert amount to base units
                $currency = Currency::where('code', $result['payload']['CurrencyCode'])->firstOrFail();
                $multiplier = pow(10, $currency->decimalPlaces);
                $amount = $multiplier * $result['payload']['Amount'];

                // Find/create originator and beneficiary
                $debitOrCredit = $result['payload']['DebitCreditCode'];
                if ($debitOrCredit == 'Debit') {
                    $originatorIdenfifier = 'fps::' . $result['payload']['Account_Iban'];
                    $beneficiaryIdenfifier = 'fps::' . $result['payload']['CounterpartAccount_Iban'];
                } else {
                    $originatorIdenfifier = 'fps::' . $result['payload']['CounterpartAccount_Iban'];
                    $beneficiaryIdenfifier = 'fps::' . $result['payload']['Account_Iban'];
                }

                foreach ([$originatorIdenfifier, $beneficiaryIdenfifier] as $accountIdentifier) {
                    $account = Account::firstOrCreate(
                        ['identifier' => $accountIdentifier],
                        [
                            'network' => 'FPS',
                            'customer_id' => 0,
                        ]
                    );
                    if ($account->wasRecentlyCreated) {
                        Log::info('Account created: ' . $account->identifier);
                    }
                }

                // Make DTO
                array_push(
                    $DTOs,
                    new PaymentDTO(
                        network: 'FPS',
                        identifier: (string) 'enm::' . $result['payload']['EndToEndTransactionId'],
                        amount: (int) $amount,
                        currency_id: (int) $currency->id,
                        originator_id: (int) Account::where('identifier', $originatorIdenfifier)->first()->id,
                        beneficiary_id: (int) Account::where('identifier', $beneficiaryIdenfifier)->first()->id,
                        memo: (string) $result['payload']['Reference'],
                        timestamp: (string) date(
                            'Y-m-d H:i:s',
                            strtotime($result['payload']['SettledTime'])
                        ),
                    )
                );
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $error = sprintf('[%s],[%d] ERROR:[%s]', __METHOD__, __LINE__, json_encode($e->getMessage(), true));
                Log::error($error);
            }
        }
        return $DTOs;
    }
}
