<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\MultiDomain\MoneyConverter;
use App\Http\Controllers\Accounts\AccountDTO;
use App\Http\Controllers\Accounts\AccountSynchronizer;
use App\Models\Account;
use App\Models\Currency;

class PaymentResponseAdapterENM implements PaymentResponseAdapterInterface
{
    /**
     * Convert an IBAN into this system's
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
    public function adapt(
        array $responseBody
    ): array {
        $DTOs = [];
        foreach ($responseBody['results'] as $result) {
            /*💬*/ //print_r($result);

            // Shift focus to the payload array
            $result = $result['payload'];

            try {
                // Determine the currency
                $currency = Currency::
                        where(
                            'code',
                            $result['CurrencyCode']
                        )->firstOrFail();

                // Convert amount to base units
                $amount = (new MoneyConverter())
                    ->convert(
                        amount: $result['Amount'],
                        currency: $currency
                    );

                //Determine originator / beneficiary
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
                $accountDTOs = [];
                foreach (
                    [
                        $originatorIdenfifier,
                        $beneficiaryIdenfifier
                    ] as $accountIdentifier
                ) {
                    // Debits use labels (assumed account names)
                    if ($debitOrCredit == 'Debit') {
                        $label = $result['CounterpartAccount_TransactionOwnerName'];
                        $networkAccountName = '';
                    // Credits provide confirmed network account names
                    } else {
                        $label = '';
                        $networkAccountName = $result['CounterpartAccount_TransactionOwnerName'];
                    }

                    // Create the DTO
                    array_push(
                        $accountDTOs,
                        new AccountDTO(
                            network: (string) 'FPS',
                            identifier: (string) $accountIdentifier,
                            customer_id: 0,
                            networkAccountName: $networkAccountName,
                            label: $label,
                            currency_id: $currency->id,
                            balance: 0
                        )
                    );
                }

                (new AccountSynchronizer())
                    ->setDTOs(DTOs: $accountDTOs)
                    ->createNewAccounts();

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
            } catch (Exception $e) {
                $this->error(__METHOD__ . ' [' . __LINE__ . ']');
                Log::error(__METHOD__ . ' [' . __LINE__ . ']');
            }
        }

        return $DTOs;
    }
}
