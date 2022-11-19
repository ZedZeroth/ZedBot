<?php

namespace App\Http\Controllers\MultiDomain;

interface SynchronizerInterface
{
    /**
     * Uses DTOs to create models for
     * any that do not already exist.
     *
     * @param array $DTOs
     */
    public function sync(
        array $DTOs
    ): void;
}
