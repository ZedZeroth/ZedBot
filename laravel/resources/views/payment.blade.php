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
        <h2>Network:
            <a href="/{{ $payment->network }}/payments">
                {{ $payment->network }}
            </a>
        </h2>
        <p>Timestamp: {{ $payment->timestamp }}</p>

        <h3>Memo: {{ $payment->memo }}</h3>

        <h3>
            Money: {{ $payment->formatAmount() }}
            <a href='/currency/{{ $payment->currency()->first()->code }}'>
                {{ $payment->currency->code }}
            </a>
        </h3>

        <h3>
            Accounts:<br>
            <a href='/account/{{ $payment->originator->identifier }}'>
                {{ $payment->originator->identifier }}
            </a>
            <br>&rarr;<br>
            <a href='/account/{{ $payment->beneficiary->identifier }}'>
            {{ $payment->beneficiary->identifier }}
            </a>
        </h3>

        <p>Identifier: {{ $payment->identifier }}</p>

    </body>
</html>