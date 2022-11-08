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
        /* Create payments if they do not exist */
        if (!Payment::all()->count()) {
            $this->upsertNew();
        }

        /* Create a new payment */
        $this->create();

        /* Show all payments */
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
     * Inserts a new relational payment.
     *
     */
    private function create()
    {
        /* Testing... */
        $payment = Payment::factory()
            //->hasCurrency(1, ['code' => 'BTC'])
            ->create(
                [
                    'amount' => 0,
                    'currency' => 'GBP'
                ]
            );
    }

    /**
     * Upsert any new payments into the database from API calls.
     *
     */
    private function upsertNew()
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
