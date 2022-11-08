<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'currency',
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
}
