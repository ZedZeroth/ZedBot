<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
     * Show one payment by ID.
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
        // Factory advantages?
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
     * Fetches recent payments from external platforms
     * and inserts any new ones.
     *
     * @param string $platform
     * @return array
     */
    public function sync($platform)
    {
        // Fetch the payment data
        $adapterClass = 'App\Http\Controllers\PaymentAdapterFor' . $platform;
        $recentPaymentDTOs = (new PaymentFetcher())
            ->fetch(new $adapterClass());

        foreach ($recentPaymentDTOs as $dto) {
            echo Payment::firstOrCreate(
                ['platformIdentifier' => $dto->platformIdentifier],
                [
                    'platform' => $dto->platform,
                    'currency' => $dto->currency,
                    'amount' => $dto->amount,
                    'platformIdentifier' => $dto->platformIdentifier,
                    'publicIdentifier' => $dto->publicIdentifier,
                    'timestamp' => $dto->timestamp,
                ]
            );
        }

        return $recentPaymentDTOs;
    }
}
