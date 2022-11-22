<?php

declare(strict_types=1);

use App\Http\Controllers\AccountController;
use Illuminate\View\View;
use App\Http\Controllers\Accounts\AccountSynchonizer;
use App\Console\Commands\SyncCommandDTO;

// construct
test('new --> AccountController')
    ->expect(fn() => new AccountController())
    ->toBeInstanceOf(AccountController::class);

// showAll
test('showAll --> View')
    ->expect(fn() => (new AccountController())->showAll())
    ->toBeInstanceOf(View::class);

// showByIdentifier
test('showByIdentifier --> correct parameters --> View')
    ->expect(fn() => (new AccountController())->showByIdentifier(env('ZED_TEST_ACCOUNT_IDENTIFIER')))
    ->toBeInstanceOf(View::class);

test('showByIdentifier --> no parameters --> TypeError')
    ->expectException(ArgumentCountError::class)
    ->expect(fn() => (new AccountController())->showByIdentifier())
    ->toBeInstanceOf(View::class);

test('showByIdentifier --> incorrect parameter type --> TypeError')
    ->expectException(TypeError::class)
    ->expect(fn() => (new AccountController())->showByIdentifier(0))
    ->toBeInstanceOf(View::class);

test('showByIdentifier --> incorrect identifier --> ModelNotFoundException')
    ->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class)
    ->expect(fn() => (new AccountController())->showByIdentifier('0'))
    ->toBeInstanceOf(View::class);

// showNetworks
test('showNetworks --> View')
    ->expect(fn() => (new AccountController())->showNetworks())
    ->toBeInstanceOf(View::class);

// showOnNetwork
test('showOnNetwork --> correct parameters --> View')
    ->expect(fn() => (new AccountController())->showOnNetwork('FPS'))
    ->toBeInstanceOf(View::class);

test('showOnNetwork --> no parameters --> TypeError')
    ->expectException(ArgumentCountError::class)
    ->expect(fn() => (new AccountController())->showOnNetwork())
    ->toBeInstanceOf(View::class);

test('showOnNetwork --> incorrect parameter type --> TypeError')
    ->expectException(TypeError::class)
    ->expect(fn() => (new AccountController())->showOnNetwork(0))
    ->toBeInstanceOf(View::class);

test('showOnNetwork --> incorrect identifier --> ModelNotFoundException')
    ->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class)
    ->expect(fn() => (new AccountController())->showOnNetwork('0'))
    ->toBeInstanceOf(View::class);

// sync
test('sync --> correct parameters --> null')
    ->expect(fn() => (new AccountController())->sync(
        new SyncCommandDTO(
            provider: 'ENM',
            numberToFetch: 0
        )
    ))
    ->toBeNull();

test('sync --> no parameters --> ArgumentCountError')
    ->expectException(ArgumentCountError::class)
    ->expect(fn() => (new AccountController())->sync())
    ->toBeNull();

test('sync --> incorrect parameter type --> TypeError')
    ->expectException(TypeError::class)
    ->expect(fn() => (new AccountController())->sync(0))
    ->toBeNull();

test('sync --> incorrect provider --> Error')
    ->expectException(Error::class)
    ->expect(fn() => (new AccountController())->sync(
        new SyncCommandDTO(
            provider: '0',
            numberToFetch: 0
        )
    ))
    ->toBeNull();

test('sync --> incorrect numberToFetch type --> Error')
    ->expectException(TypeError::class)
    ->expect(fn() => (new AccountController())->sync(
        new SyncCommandDTO(
            provider: 'ENM',
            numberToFetch: '0'
        )
    ))
    ->toBeNull();
