<?php

namespace App\Http\Controllers\Payments;

use Illuminate\View\View;
use App\Http\Controllers\MultiDomain\ViewerInterface;
use App\Models\Payment;

class PaymentViewer implements ViewerInterface
{
    /**
     * Show all payments (on every network).
     *
     * @return View
     */
    public function showAll(): View
    {
        return view('payments', [
            'payments' => Payment::all()
                ->sortByDesc('timestamp')
        ]);
    }

    /**
     * Show the profile for a specific payment.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View {
        return view('payment', [
            'payment' =>
                Payment::where('id', $identifier)
                    ->first()
        ]);
    }

    /**
     * Show all payment networks.
     *
     * @return View
     */
    public function showPaymentNetworks(): View
    {
        return view(
            'payment-networks',
            [
                'payments' => Payment::all()
                    ->unique('network')
            ]
        );
    }

    /**
     * Show all payments on one payment network.
     *
     * @param string $network
     * @return View
     */
    public function showPaymentsOnNetwork(
        string $network
    ): View {
        return view(
            'network-payments',
            [
                'network' => $network,
                'paymentsOnNetwork'
                    => Payment::where('network', $network)
                        ->get()
            ]
        );
    }
}
