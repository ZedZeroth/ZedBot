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
    protected $guarded = [
        'id',
    ];

    /**
     * Defines the payment's currency relation.
     */
    public function currency()
    {
        return $this->belongsTo(
            Currency::class,
            'currency',
            'code'
        );
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
