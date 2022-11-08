<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/payments">Back</a>

        <h1>Payment: {{ $payment->id }}</h1>

        <h2>PlatformId: {{ $payment->platformId }}</h2>
        <h2>PublicId: {{ $payment->publicId }}</h2>

        <p>Amount: {{ $payment->amount }}</p>
        <p>Currency:
            <a href='/currency/{{ $payment->currency()->first()->code }}'>
                {{ $payment->currency()->first()->code }}
                ({{ $payment->currency()->first()->nameSingular }})
            </a>
        </p>

    </body>
</html>