<?php

declare(strict_types=1);

use App\Http\Controllers\Currencies\CurrencyViewer;
use Illuminate\View\View;

// construct
test('new --> CurrencyViewer')
    ->expect(fn() => new CurrencyViewer())
    ->toBeInstanceOf(CurrencyViewer::class);

// showAll
test('showAll --> View')
    ->expect(fn() => (new CurrencyViewer())->showAll())
    ->toBeInstanceOf(View::class);

// showByIdentifier
test('showByIdentifier --> correct parameters --> View')
    ->expect(fn() => (new CurrencyViewer())->showByIdentifier('GBP'))
    ->toBeInstanceOf(View::class);

test('showByIdentifier --> no parameters --> ArgumentCountError')
    ->expectException(ArgumentCountError::class)
    ->expect(fn() => (new CurrencyViewer())->showByIdentifier())
    ->toBeInstanceOf(View::class);

test('showByIdentifier --> incorrect parameter type --> TypeError')
    ->expectException(TypeError::class)
    ->expect(fn() => (new CurrencyViewer())->showByIdentifier(0))
    ->toBeInstanceOf(View::class);

test('showByIdentifier --> incorrect identifier --> ModelNotFoundException')
    ->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class)
    ->expect(fn() => (new CurrencyViewer())->showByIdentifier('XXX'))
    ->toBeInstanceOf(View::class);
