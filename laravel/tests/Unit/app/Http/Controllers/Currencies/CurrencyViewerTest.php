<?php

//declare(strict_types=1);

use App\Http\Controllers\Currencies\CurrencyViewer;
use Illuminate\View\View;

it('should assert showAll returns View', function () {
    $this->assertInstanceOf(
        View::class,
        (new CurrencyViewer())->showAll()
    );
});

it('should also assert showAll returns View', function () {
    expect(
        (new CurrencyViewer())->showAll()
    )->toBeInstanceOf(View::class);
});

it('asserts construction constructs...', function () {
    $this->assertInstanceOf(
        CurrencyViewer::class,
        new CurrencyViewer()
    );
});
