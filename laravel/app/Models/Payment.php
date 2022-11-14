<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Defines the payment's currency.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Defines the originator account.
     */
    public function originator()
    {
        return $this->belongsTo(Account::class, 'originator_id');
    }

    /**
     * Defines the beneficiary account.
     */
    public function beneficiary()
    {
        return $this->belongsTo(Account::class, 'beneficiary_id');
    }

    /**
     * Formats the payment amount into non-base units.
     *
     * @return string
     */
    public function formatAmount()
    {
        return number_format(
            $this->amount / pow(10, $this->currency()->first()->decimalPlaces),
            2,
            '.',
            ',',
        );
    }
}
