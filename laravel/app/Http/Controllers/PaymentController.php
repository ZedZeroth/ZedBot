<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Show all payments.
     *
     * @return \Illuminate\View\View
     */
    public function showAll()
    {
        return view('payments', [
            'payments' => Payment::all()
        ]);
    }

    /**
     * Show the profile for a given payment.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return view('payment', [
            'payment' => Payment::findOrFail($id)
        ]);
    }

    /**
     * Inserts a new payment.
     *
     */
    public function create()
    {
        /* Testing... */
        $payment = Payment::factory()
            /* How does this work? */
            //->hasCurrency(1, ['code' => 'BTC'])
            ->create(
                [
                    'amount' => rand(1, 100),
                    'currency' => 'GBP'
                ]
            );
    }

    /**
     * Upsert (insert new / update existing) payments.
     *
     */
    private function upsert()
    {
        /* Testing... */
        $payment = Payment::updateOrCreate(
            ['id' => '1'],
            [
                'amount' => 0,
                'currency' => 'BTC'
            ]
        );
    }
}
