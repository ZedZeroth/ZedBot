<?php

?>
<!DOCTYPE html>
@livewireScripts
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <h1>ZedBot v0.0</h1>

         
        

        <h2>Models</h2>

        <ol>
            <li>
                <a href="currencies">Currencies</a>
                <livewire:currency-populator />
            </li>
            <li>
                <a href="payments">Payments</a>
                <livewire:payment-fetcher />
            </li>
        </ol>

    </body>
</html>