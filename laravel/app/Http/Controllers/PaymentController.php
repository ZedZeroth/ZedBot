<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Http\Controllers\Payments\PaymentViewer;
use App\Console\Commands\CommandDTO;
use App\Http\Controllers\Payments\PaymentSynchronizer;
use App\Http\Controllers\Payments\PaymentFetcher;
use App\Http\Controllers\MultiDomain\ResponseDecoder;

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
     * Fetches recent payments from external providers
     * and creates any new ones that do not exist.
     *
     * @param CommandDTO $dto
     * @return void
     */
    public function sync(
        CommandDTO $dto
    ): void {
        (new PaymentSynchronizer())
            ->selectAdapters(paymentProvider: $dto->data['paymentProvider'])
            ->requestPaymentsAndAdaptResponse(
                numberOfPaymentsToFetch: $dto->data['numberOfPaymentsToFetch'],
                responseDecoder: new ResponseDecoder()
            )
            ->createNewPayments();
        return;
    }
}
