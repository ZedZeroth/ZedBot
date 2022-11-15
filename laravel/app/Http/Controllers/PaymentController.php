<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Http\Controllers\Payments\PaymentViewer;
use App\Http\Controllers\Payments\PaymentSynchroniser;

class PaymentController extends Controller
{
    /**
     * Show all payments (on every network).
     *
     * @return View
     */
    public function showAll(): View
    {
        return (new PaymentViewer())->showAll();
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
        return (new PaymentViewer())
            ->showByIdentifier(
                identifier: $identifier
            );
    }

    /**
     * Show all payment networks.
     *
     * @return View
     */
    public function showPaymentNetworks(): View
    {
        return (new PaymentViewer())->showPaymentNetworks();
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
        return (new PaymentViewer())
            ->showPaymentsOnNetwork(
                network: $network
            );
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
