<?php

namespace App\Http\Controllers;

class PaymentController extends Controller
{
    /**
     * Show all payments.
     *
     * @return \Illuminate\View\View
     */
    public function viewAll()
    {
        return (new PaymentViewer())->viewAll();
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
     * @param string $platform
     * @param int $numberOfPayments
     * @return array
     */
    public function sync($platform, $numberOfPayments)
    {
        return (new PaymentSynchroniser())
            ->sync(
                platform: $platform,
                numberOfPayments: $numberOfPayments,
            );
    }
}
