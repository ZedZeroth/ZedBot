<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    /**
     * The feault attributes.
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
}
