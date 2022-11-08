<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="..">Back</a>

        <h1>Payments</h1>

        <?php
        foreach ($payments as $payment) {
            echo "
                <p>
                    $payment->id.
                    <a href='payment/$payment->id'>
                        $payment->money->amount
                    </a>
                </p>
            ";
        }
        ?>

    </body>
</html>