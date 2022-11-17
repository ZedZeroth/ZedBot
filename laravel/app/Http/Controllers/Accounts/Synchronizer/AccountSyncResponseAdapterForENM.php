<?php

namespace App\Http\Controllers\Accounts\Synchronizer;

use Illuminate\Support\Facades\Log;
use App\Models\Account;
use App\Models\Currency;
use App\Http\Controllers\Accounts\AccountDTO;

class AccountSyncResponseAdapterForENM implements AccountSyncResponseAdapterInterface
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
     * Sets the response body.
     *
     * @param array $responseBody
     * @return accountSyncResponseAdapterInterface
     */
    public function setResponseBody(
        array $responseBody
    ): accountSyncResponseAdapterInterface {
        $this->responseBody = $responseBody;
        return $this;
    }

    /**
     * Build the account DTOs.
     *
     * @return accountSyncResponseAdapterInterface
     */
    public function buildAccountDTOs(): accountSyncResponseAdapterInterface
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
