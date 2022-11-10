<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/payments">↩️</a>

        <h1>Payment: {{ $payment->id }}</h1>

        <h3>Platform Identifier: {{ $payment->platformIdentifier }}</h3>
        <h3>Public Identifier: {{ $payment->publicIdentifier }}</h3>

        <p>Amount: {{ $payment->formatAmount() }}</p>
        <p>Currency:
            <a href='/currency/{{ $payment->currency()->first()->code }}'>
                {{ $payment->currency()->first()->code }}
                &bull;
                {{ $payment->currency()->first()->symbol }}
                &bull;
                {{ $payment->currency()->first()->nameSingular }}
            </a>
            <p>Timestamp: {{ $payment->timestamp }}</p>
        </p>

    </body>
</html>