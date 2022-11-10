<?php

?>
<!DOCTYPE html>
@livewireScripts
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <h1>ZedBot v0.1</h1>

         
        

        <h2>Models</h2>

        <ol>
            <li>
                <a href="currencies">Currencies</a>
                <livewire:currency-populator-component />
            </li>
            <li>
                <a href="payments">Payments</a>
                <livewire:payment-fetcher-component />
            </li>
        </ol>

    </body>
</html>