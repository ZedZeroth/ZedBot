<?php

namespace App\Http\Controllers\MultiDomain;

use Illuminate\View\View;

interface ViewerInterface
{
    /**
     * Show all instances of one model.
     *
     * @return View
     */
    public function showAll(): View;

    /**
     * Show the profile for a specific model instance.
     *
     * @param string $identifier
     * @return View
     */
    public function showByIdentifier(
        string $identifier
    ): View;

    /**
     * Show all model networks.
     *
     * @return View
     */
    public function showNetworks(): View;

    /**
     * Show all models on one network.
     *
     * @param string $network
     * @return View
     */
    public function showOnNetwork(
        string $network
    ): View;
}
