<?php

declare(strict_types=1);

use App\Console\Commands\SyncCommandDTO;

// construct
test('new --> correct parameters --> SyncCommandDTO')
    ->expect(fn() => new SyncCommandDTO(
        provider: 'ENM',
        numberToFetch: 0
    ))
    ->toBeInstanceOf(SyncCommandDTO::class);

test('new --> no parameters --> ArgumentCountError')
    ->expectException(ArgumentCountError::class)
    ->expect(fn() => new SyncCommandDTO())
    ->toBeInstanceOf(SyncCommandDTO::class);

test('new --> incorrect parameter type --> TypeError')
    ->expectException(TypeError::class)
    ->expect(fn() => new SyncCommandDTO(0))
    ->toBeInstanceOf(SyncCommandDTO::class);

test('new --> incorrect provider type --> TypeError')
    ->expectException(TypeError::class)
    ->expect(fn() => new SyncCommandDTO(
        provider: 0,
        numberToFetch: 0
    ))
    ->toBeInstanceOf(SyncCommandDTO::class);

test('new --> incorrect numberToFetch type --> TypeError')
    ->expectException(TypeError::class)
    ->expect(fn() => new SyncCommandDTO(
        provider: 'ENM',
        numberToFetch: '0'
    ))
    ->toBeInstanceOf(SyncCommandDTO::class);
