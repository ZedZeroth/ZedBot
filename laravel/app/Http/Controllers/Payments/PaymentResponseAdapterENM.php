<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Support\Facades\Log;
use App\Models\Account;
use App\Models\Currency;

class PaymentResponseAdapterENM implements PaymentResponseAdapterInterface
{
    /**
     * Converts an IBAN into this system's
     * FPS account identifier.
     *
     * @param string $iban
     * @return string
     */
    public function convertIban(
        string $iban
    ): string {
        return 'fps'
            . '::' . 'gbp'
            . '::' . substr($iban, -14, 6) // Sort code
            . '::' . substr($iban, -8); // Account number
    }
    
    /**
     * Converts an ENM payment request response into
     * an array of payment DTOs for the synchronizer.
     *
     * @param array $responseBody
     * @return array
     */
    public function respond(
        array $responseBody
    ): array {
        $DTOs = [];
        foreach ($responseBody['results'] as $result) {
            /*💬*/ //print_r($result);

            // Shift focus
            $result = $result['payload'];

            try {
                /**
                 * Find currency and convert
                 * amount to base units.
                 *
                 */
                $currency = Currency::
                        where(
                            'code',
                            $result['CurrencyCode']
                        )->firstOrFail();

                $multiplier = pow(
                    10,
                    $currency->decimalPlaces
                );

                $amount = $multiplier
                    * $result['Amount'];

                /**
                 * Determine originator / beneficiary.
                 *
                 */
                $debitOrCredit = $result['DebitCreditCode'];
                if ($debitOrCredit == 'Debit') {
                    $originatorIdenfifier = $this->convertIban($result['Account_Iban']);
                    $beneficiaryIdenfifier = $this->convertIban($result['CounterpartAccount_Iban']);
                } else {
                    $originatorIdenfifier = $this->convertIban($result['CounterpartAccount_Iban']);
                    $beneficiaryIdenfifier = $this->convertIban($result['Account_Iban']);
                }

                /**
                 * Create originator/beneficiary accounts
                 * if they don't exist and use
                 * updateOrCreate to override their
                 * networkAccountName if they do.
                 *
                 */
                foreach (
                    [
                        $originatorIdenfifier,
                        $beneficiaryIdenfifier
                    ] as $accountIdentifier
                ) {
                    // Debits use assumed account names
                    if ($debitOrCredit == 'Debit') {
                        $account = Account::firstOrCreate(
                            ['identifier' => $accountIdentifier],
                            [
                                'network' => 'FPS',
                                'customer_id' => 0,
                                'assumedAccountName' => $result['CounterpartAccount_TransactionOwnerName'],
                                'currency_id' => $currency->id,
                                'balance' => 0
                            ]
                        );
                    // Credits provide network account names
                    } else {
                        $account = Account::updateOrCreate(
                            ['identifier' => $accountIdentifier],
                            [
                                'network' => 'FPS',
                                'customer_id' => 0,
                                'networkAccountName' => $result['CounterpartAccount_TransactionOwnerName'],
                                'currency_id' => $currency->id,
                                'balance' => 0
                            ]
                        );
                    }
                    if ($account->wasRecentlyCreated) {
                        Log::info(
                            'Account created: '
                            . $account->identifier
                        );
                    }
                }

                /**
                 * Build the payment DTO.
                 *
                 */
                array_push(
                    $DTOs,
                    new PaymentDTO(
                        network: (string) 'FPS',
                        identifier: (string) 'enm::'
                            . $result['EndToEndTransactionId'],
                        amount: (int) $amount,
                        currency_id: (int) $currency->id,
                        originator_id: (int) Account::
                            where('identifier', $originatorIdenfifier)
                            ->first()->id,
                        beneficiary_id: (int) Account::
                            where('identifier', $beneficiaryIdenfifier)
                            ->first()->id,
                        memo: (string) $result['Reference'],
                        timestamp: (string) date(
                            'Y-m-d H:i:s',
                            strtotime($result['SettledTime'])
                        ),
                    )
                );
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $error = sprintf(
                    '[%s],[%d] ERROR:[%s]',
                    __METHOD__,
                    __LINE__,
                    json_encode($e->getMessage(), true)
                );
                Log::error($error);
            }
        }

        return $DTOs;
    }
}
