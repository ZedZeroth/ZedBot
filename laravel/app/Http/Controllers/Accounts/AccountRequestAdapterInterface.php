<?php

namespace App\Http\Controllers\Accounts;

interface AccountRequestAdapterInterface
{
    /**
     * Requests financial/monetary accounts from an API.
     *
     * @param int $numberOfAccounts
     * @return array
     */
    public function request(
        int $numberOfAccounts
    ): array;
}
