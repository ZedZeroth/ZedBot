<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\MultiDomain\MoneyFormatter;
use App\Models\Currency;

class Account extends Model
{
    /**
     * The default attributes.
     *
     * @var array<int, string>
     */
    protected $attributes = [
        'networkAccountName' => '',
        'assumedAccountName' => ''
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
    * Get the incoming payments for this account.
    */
    public function credits()
    {
        return $this->hasMany(Payment::class, 'beneficiary_id');
    }

    /**
    * Get the outgoing payments for this account.
    */
    public function debits()
    {
        return $this->hasMany(Payment::class, 'originator_id');
    }

    /**
    * Determine the currency for this account.
    */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Formats the account balance into
     * its standard denomination.
     *
     * @return string
     */
    public function formatBalance()
    {
        return (new MoneyFormatter())->format(
            amount: $this->balance,
            currency: $this->currency()->first()
        );
    }
}
