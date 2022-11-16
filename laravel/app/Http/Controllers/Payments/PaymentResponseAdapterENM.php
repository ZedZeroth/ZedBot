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
     * Converts an ENM payment request response into
     * an array of account and payment DTOs to be
     * sunchronized.
     *
     * @param array $responseBody
     * @return array
     */
    public function adapt(
        array $responseBody
    ): array {
        $this->responseBody = $responseBody;

        return $this
            ->buildAccountDTOs()
            ->syncAccountDTOs()
            ->buildPaymentDTOs()
            ->returnAccountDTOs();
    }

    /**
     * Build the account DTOs.
     *
     * @return PaymentResponseAdapterInterface
     */
    private function buildAccountDTOs(): PaymentResponseAdapterInterface
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
            foreach ($identifiers as $accountIdentifier) {
                // Debits use labels (assumed account names)
                if ($result['DebitCreditCode'] == 'Debit') {
                    $label = $result['CounterpartAccount_TransactionOwnerName'];
                    $networkAccountName = '';
                // Credits provide confirmed network account names
                } else {
                    $label = '';
                    $networkAccountName = $result['CounterpartAccount_TransactionOwnerName'];
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
     * @return PaymentResponseAdapterInterface
     */
    private function syncAccountDTOs(): PaymentResponseAdapterInterface
    {
        (new AccountSynchronizer())
                    ->setDTOs(DTOs: $this->accountDTOs)
                    ->createNewAccounts();
        return $this;
    }

    /**
     * Build the payment DTOs.
     *
     * @return PaymentResponseAdapterInterface
     */
    private function buildPaymentDTOs(): PaymentResponseAdapterInterface
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
    private function returnAccountDTOs(): array
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
