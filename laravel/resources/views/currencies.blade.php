<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="..">Back</a>

        <h1>Currencies</h1>

        <?php
        foreach ($currencies as $currency) {
            echo "
                <p>
                    $currency->id.
                    <a href='currency/$currency->code'>
                        $currency->code
                    </a>
                </p>
            ";
        }
        ?>

    </body>
</html>