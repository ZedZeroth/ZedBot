<?php

declare(strict_types=1);

use App\Http\Controllers\AccountController;
use Illuminate\View\View;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\Currencies\CurrencyPopulator;

// construct
test('new --> CurrencyController')
    ->expect(fn() => new CurrencyController())
    ->toBeInstanceOf(CurrencyController::class);

// showAll
test('showAll --> View')
    ->expect(fn() => (new CurrencyController())->showAll())
    ->toBeInstanceOf(View::class);

// showByIdentifier
test('showByIdentifier --> correct parameters --> View')
    ->expect(fn() => (new CurrencyController())->showByIdentifier('GBP'))
    ->toBeInstanceOf(View::class);

test('showByIdentifier --> no parameters --> ArgumentCountError')
    ->expectException(ArgumentCountError::class)
    ->expect(fn() => (new CurrencyController())->showByIdentifier())
    ->toBeInstanceOf(View::class);

test('showByIdentifier --> incorrect parameter type --> TypeError')
    ->expectException(TypeError::class)
    ->expect(fn() => (new CurrencyController())->showByIdentifier(0))
    ->toBeInstanceOf(View::class);

test('showByIdentifier --> incorrect identifier --> ModelNotFoundException')
    ->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class)
    ->expect(fn() => (new CurrencyController())->showByIdentifier('XXX'))
    ->toBeInstanceOf(View::class);

// populate
test('populate --> null')
    ->expect(fn() => (new CurrencyController())->populate())
    ->toBeNull();
