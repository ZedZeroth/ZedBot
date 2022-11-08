<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/currencies">Back</a>

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

        <h3>Related payments</h3>

        <?php
        foreach ($currency->payments()->get() as $payment) {
            echo "
                <p>
                    $payment->id.
                    <a href='/payment/$payment->id'>
                        $payment->amount
                        $payment->currency
                    </a>
                </p>
            ";
        }
        ?>

    </body>
</html>