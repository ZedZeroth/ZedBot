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
     */
    private array $responseBody;

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
    public function buildAndSyncAccountDTOs(): PaymentSyncResponseAdapterInterface
    {
        $accountDTOs = [];
        /*💬*/ //print_r($this->responseBody);
        foreach (
            $this->responseBody['results'][env('ZED_ENM_ACCOUNT_CODE')]['results']
            as $result
        ) {
            /*💬*/ //print_r($result);

            // Determine the currency
            $currency = Currency::
                    where(
                        'code',
                        $result['transactionCurrency']
                    )->firstOrFail();

            // Determine beneficiary / originator
            $beneficiary = explode(', ', $result['beneficiary']);
            $beneficiarylabel = $beneficiary[0];
            $beneficiaryAccountIdentifier =
                $this->convertIbanToIdentifier($beneficiary[1]);

            if ($beneficiarylabel == env('ZED_ENM_ACCOUNT_NAME')) {
                $originator = explode(', ', $result['counterparty']);
                $originatorNetworkAccountName = $originator[0];
                $originatorLabel = '';
                $originatorAccountIdentifier =
                    $this->convertIbanToIdentifier($originator[1]);
            } else {
                $originatorNetworkAccountName = '';
                $originatorLabel = env('ZED_ENM_ACCOUNT_NAME');
                $originatorAccountIdentifier =
                    $this->convertIbanToIdentifier($result['accno']);
            }

            // Create the originator DTO
            array_push(
                $accountDTOs,
                new AccountDTO(
                    network: (string) 'FPS',
                    identifier: (string) $originatorAccountIdentifier,
                    customer_id: 0,
                    networkAccountName: $originatorNetworkAccountName,
                    label: $originatorLabel,
                    currency_id: $currency->id,
                    balance: 0
                )
            );

            // Create the beneficiary DTO
            array_push(
                $accountDTOs,
                new AccountDTO(
                    network: (string) 'FPS',
                    identifier: (string) $beneficiaryAccountIdentifier,
                    customer_id: 0,
                    networkAccountName: '',
                    label: $beneficiarylabel,
                    currency_id: $currency->id,
                    balance: 0
                )
            );
        }

        (new AccountSynchronizer())
                    ->setDTOs(DTOs: $accountDTOs)
                    ->createNewAccounts();
        return $this;

        return $this;
    }

    /**
     * Build the payment DTOs.
     *
     * @return array
     */
    public function buildPaymentDTOs(): array
    {
        $paymentDTOs = [];
        /*💬*/ //print_r($this->responseBody);
        foreach (
            $this->responseBody['results'][env('ZED_ENM_ACCOUNT_CODE')]['results']
            as $result
        ) {
            /*💬*/ //print_r($result);

            // Determine the currency
            $currency = Currency::
                    where(
                        'code',
                        $result['transactionCurrency']
                    )->firstOrFail();

            // Convert amount to base units
            $amount = (new MoneyConverter())
            ->convert(
                amount: $result['transactionAmount'],
                currency: $currency
            );

            // Determine beneficiary / originator
            $beneficiary = explode(', ', $result['beneficiary']);
            $beneficiarylabel = $beneficiary[0];
            $beneficiaryAccountIdentifier =
                $this->convertIbanToIdentifier($beneficiary[1]);

            if ($beneficiarylabel == env('ZED_ENM_ACCOUNT_NAME')) {
                $originator = explode(', ', $result['counterparty']);
                $originatorNetworkAccountName = $originator[0];
                $originatorLabel = '';
                $originatorAccountIdentifier =
                    $this->convertIbanToIdentifier($originator[1]);
            } else {
                $originatorNetworkAccountName = '';
                $originatorLabel = env('ZED_ENM_ACCOUNT_NAME');
                $originatorAccountIdentifier =
                    $this->convertIbanToIdentifier($result['accno']);
            }

            array_push(
                $paymentDTOs,
                new PaymentDTO(
                    network: (string) 'FPS',
                    identifier: (string) 'enm::'
                        . $result['id'],
                    amount: (int) $amount,
                    currency_id: (int) $currency->id,
                    originator_id: (int) Account::
                        where('identifier', $originatorAccountIdentifier)
                        ->first()->id,
                    beneficiary_id: (int) Account::
                        where('identifier', $beneficiaryAccountIdentifier)
                        ->first()->id,
                    memo: (string) $result['paymentReference'],
                    timestamp: (string) date(
                        'Y-m-d H:i:s',
                        strtotime($result['transactionTimeLocal'])
                    ),
                )
            );
        }

        return $paymentDTOs;
    }

    /**
     * Convert an IBAN into this system's
     * FPS account identifier.
     *
     * @param string $iban
     * @return string
     */
    public function convertIbanToIdentifier(
        string $iban
    ): string {
        return 'fps'
            . '::' . 'gbp'
            . '::' . substr($iban, -14, 6) // Sort code
            . '::' . substr($iban, -8); // Account number
    }
}
