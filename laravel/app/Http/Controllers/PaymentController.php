<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Payments\PaymentViewer;
use App\Http\Controllers\Payments\PaymentSynchroniser;

class PaymentController extends Controller
{
    /**
     * Show all payment networks.
     *
     * @return \Illuminate\View\View
     */
    public function viewNetworks()
    {
        return (new PaymentViewer())->viewNetworks();
    }

    /**
     * Show all payments on one payment network.
     *
     * @param  string  $network
     * @return \Illuminate\View\View
     */
    public function viewPaymentsOnNetwork($network)
    {
        return (new PaymentViewer())->viewPaymentsOnNetwork(network: $network);
    }

    /**
     * Show one payment by its ID.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function viewById($id)
    {
        return (new PaymentViewer())->viewById(id: $id);
    }

    /**
     * Fetches recent payments from external platforms
     * and inserts any new ones.
     *
     * @param string $provider
     * @param int $numberOfPayments
     * @return array
     */
    public function sync($provider, $numberOfPayments)
    {
        return (new PaymentSynchroniser())
            ->sync(
                provider: $provider,
                numberOfPayments: $numberOfPayments,
            );
    }
}
