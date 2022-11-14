<?php

namespace App\Http\Controllers\Payments;

use App\Models\Payment;

class PaymentViewer
{
    /**
     * Show all payment networks.
     *
     * @return \Illuminate\View\View
     */
    public function viewNetworks()
    {
        return view(
            'payment-networks',
            ['payments' => Payment::all()->unique('network')]
        );
    }

    /**
     * Show all payments on one payment network.
     *
     * @param  string  $network
     * @return \Illuminate\View\View
     */
    public function viewPaymentsOnNetwork($network)
    {
        return view(
            'network-payments',
            [
                'network' => $network,
                'paymentsOnNetwork' => Payment::where('network', $network)->get(),
            ]
        );
    }

    /**
     * Show one payment by its ID.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function viewById($id)
    {
        return view(
            'payment',
            ['payment' => Payment::where('id', $id)->firstOrFail()]
        );
    }
}
