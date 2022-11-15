<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/currencies">↩️</a>

        <h1>Currency: {{ $currency->code }}</h1>

        <h2>
            <?php echo ucfirst($currency->nameSingular); ?>
        </h2>

        <p>
            {{ $currency->symbol }}
            1
            =
            <?php
            echo
                number_format(
                    pow(10, $currency->decimalPlaces),
                    0,
                    '.',
                    ','
                )
            ?>
            {{ $currency->baseUnitNamePlural }}
        </p>

        <h3>Payments in this currency</h3>

        @foreach($currency->payments()->get() as $payment)
            <p>
                <a href='/payment/{{ $payment->id }}'>{{ $payment->timestamp }}</a>
                {{ $payment->formatAmount() }}
                {{ $payment->currency->code }}
            </p>
        @endforeach

    </body>
</html>