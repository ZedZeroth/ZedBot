<?php

namespace App\Http\Controllers\Payments\Synchronizer;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\MultiDomain\MoneyConverter;
use App\Http\Controllers\Accounts\AccountDTO;
use App\Http\Controllers\Accounts\AccountSynchronizer;
use App\Models\Account;
use App\Models\Currency;
use App\Http\Controllers\Payments\PaymentDTO;

class PaymentSyncResponseAdapterForENM implements PaymentSyncResponseAdapterInterface
{
    /**
     * Properties required by the adapter.
     *
     * @var array $responseBody
     * @var array $accountDTOs
     * @var array $paymentDTOs
     */
    private array $responseBody;
    private array $accountDTOs = [];
    private array $paymentDTOs = [];

    /**
     * Set the response body.
     *
     * @param array $responseBody
     * @return PaymentSyncResponseAdapterInterface
     */
    public function setResponseBody(
        array $responseBody
    ): PaymentSyncResponseAdapterInterface {
        $this->responseBody = $responseBody;
        return $this;
    }

    /**
     * Build the account DTOs.
     *
     * @return PaymentSyncResponseAdapterInterface
     */
    public function buildAccountDTOs(): PaymentSyncResponseAdapterInterface
    {
        foreach ($this->responseBody['results'] as $result) {
            /*💬*/ //print_r($result);

            // Shift focus to the payload array
            $result = $result['payload'];

            // Determine the currency
            $currency = Currency::
                    where(
                        'code',
                        $result['CurrencyCode']
                    )->firstOrFail();

            //Determine originator / beneficiary
            $identifiers = $this
                ->originatorOrBeneficiary(
                    Account_Iban: $result['Account_Iban'],
                    CounterpartAccount_Iban: $result['CounterpartAccount_Iban'],
                    DebitCreditCode: $result['DebitCreditCode']
                );

            /**
             * Create originator/beneficiary accounts
             * if they don't already exist.
             *
             */
            foreach ($identifiers as $originatorOrBeneficiary => $accountIdentifier) {
                $label = '';
                $networkAccountName = '';
                /**
                 * Debits use labels (assumed account names)
                 * for the beneficiary.
                 */
                if (
                    $result['DebitCreditCode'] == 'Debit'
                    and $originatorOrBeneficiary = 'beneficiary'
                ) {
                    $label = $result['CounterpartAccount_TransactionOwnerName'];
                }
                /**
                 * Credits provide confirmed network account names
                 * for the originator.
                 */
                if (
                    $result['DebitCreditCode'] == 'Credit'
                    and $originatorOrBeneficiary = 'originator'
                ) {
                    $labnetworkAccountNameel = $result['CounterpartAccount_TransactionOwnerName'];
                }

                // Create the DTO
                array_push(
                    $this->accountDTOs,
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
        }

        return $this;
    }

    /**
     * Sync the account DTOs.
     *
     * @return PaymentSyncResponseAdapterInterface
     */
    public function syncAccountDTOs(): PaymentSyncResponseAdapterInterface
    {
        (new AccountSynchronizer())
                    ->setDTOs(DTOs: $this->accountDTOs)
                    ->createNewAccounts();
        return $this;
    }

    /**
     * Build the payment DTOs.
     *
     * @return PaymentSyncResponseAdapterInterface
     */
    public function buildPaymentDTOs(): PaymentSyncResponseAdapterInterface
    {
        foreach ($this->responseBody['results'] as $result) {
            /*💬*/ //print_r($result);

            // Shift focus to the payload array
            $result = $result['payload'];

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
            $identifiers = $this
                ->originatorOrBeneficiary(
                    Account_Iban: $result['Account_Iban'],
                    CounterpartAccount_Iban: $result['CounterpartAccount_Iban'],
                    DebitCreditCode: $result['DebitCreditCode']
                );

            array_push(
                $this->paymentDTOs,
                new PaymentDTO(
                    network: (string) 'FPS',
                    identifier: (string) 'enm::'
                        . $result['EndToEndTransactionId'],
                    amount: (int) $amount,
                    currency_id: (int) $currency->id,
                    originator_id: (int) Account::
                        where('identifier', $identifiers['originator'])
                        ->first()->id,
                    beneficiary_id: (int) Account::
                        where('identifier', $identifiers['beneficiary'])
                        ->first()->id,
                    memo: (string) $result['Reference'],
                    timestamp: (string) date(
                        'Y-m-d H:i:s',
                        strtotime($result['SettledTime'])
                    ),
                )
            );
        }

        return $this;
    }

    /**
     * Return the payment DTOs.
     *
     * @return array
     */
    public function returnPaymentDTOs(): array
    {
        return $this->paymentDTOs;
    }

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
     * Determine the originator / beneficiary.
     *
     * @param string $Account_Iban
     * @param string $CounterpartAccount_Iban
     * @param string $DebitCreditCode
     * @return array
     */
    public function originatorOrBeneficiary(
        string $Account_Iban,
        string $CounterpartAccount_Iban,
        string $DebitCreditCode
    ): array {
        if ($DebitCreditCode == 'Debit') {
            $dataArray = [
                'originator' => $this->convertIban(iban: $Account_Iban),
                'beneficiary' => $this->convertIban(iban: $CounterpartAccount_Iban)
            ];
        } else {
            $dataArray = [
                'originator' => $this->convertIban(iban: $CounterpartAccount_Iban),
                'beneficiary' => $this->convertIban(iban: $Account_Iban)
            ];
        }
        return $dataArray;
    }
}
