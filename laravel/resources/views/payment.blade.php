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
        <p>Timestamp: {{ $payment->timestamp }}</p>

        <h3>Memo: {{ $payment->memo }}</h3>

        <h3>
            Money: {{ $payment->formatAmount() }}
            <a href='/currency/{{ $payment->currency()->first()->code }}'>
                {{ $payment->currency->code }}
            </a>
        </h3>

        <h3>
            Accounts:
            {{ $payment->originator->identifier }}
            &rarr;
            {{ $payment->beneficiary->identifier }}
        </h3>

        <p>Network: {{ $payment->network }}</p>
        <p>Identifier: {{ $payment->identifier }}</p>

    </body>
</html>