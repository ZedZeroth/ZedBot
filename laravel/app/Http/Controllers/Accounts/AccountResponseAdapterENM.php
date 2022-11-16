<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Support\Facades\Log;
use App\Models\Account;
use App\Models\Currency;

class AccountResponseAdapterENM implements AccountResponseAdapterInterface
{
    /**
     * Properties required by the adapter.
     *
     * @var array $responseBody
     * @var array $accountDTOs
     */
    private array $responseBody;
    private array $accountDTOs = [];

    /**
     * Converts an ENM account request response into
     * an array of account DTOs to be
     * synchronized.
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
            ->returnAccountDTOs();
    }

    /**
     * Build the account DTOs.
     *
     * @return AccountResponseAdapterInterface
     */
    public function buildAccountDTOs(): AccountResponseAdapterInterface
    {
        foreach ($this->responseBody['results'] as $result) {
            /*💬*/ //print_r($result);

            // Determine the currency
            $currency = Currency::
                    where(
                        'code',
                        'GBP'
                    )->firstOrFail();

            // Create the DTO
            array_push(
                $this->accountDTOs,
                new AccountDTO(
                    network: (string) 'FPS',
                    identifier: (string) 'fps'
                        . '::' . 'gbp'
                        . '::' . $result['sortCode']
                        . '::' . $result['accountNumber'],
                    customer_id: (int) 0,
                    networkAccountName: (string) '',
                    label: (string) $result['accountName'],
                    currency_id: (int) $currency->id,
                    balance: (int) 0,
                ),
            );
        }

        return $this;
    }

    /**
     * Return the account DTOs.
     *
     * @return array
     */
    public function returnAccountDTOs(): array
    {
        return $this->accountDTOs;
    }
}
