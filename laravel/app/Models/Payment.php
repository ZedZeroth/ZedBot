<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

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
}
