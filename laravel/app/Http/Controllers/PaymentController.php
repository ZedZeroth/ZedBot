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
     * Upsert any new payments into the database from API calls.
     *
     */
    public function upsertNew()
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
