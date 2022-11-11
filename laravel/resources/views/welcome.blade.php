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

        <table>
            <tr>
                <td>
                    <a href="currencies">Currencies</a>
                </td>
                <td>
                    <livewire:currency-populator-component />
                </td>
            </tr>
            <tr>
                <td>
                <a href="payments">Payments</a>
                </td>
                <td>
                <livewire:payment-fetcher-component />
                </td>
            </tr>
        </table>

    </body>
</html>