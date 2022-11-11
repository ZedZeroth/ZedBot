<?php

?>
<!DOCTYPE html>
@livewireScripts
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
    </head>

    <body>

        <h1>ZedBot v0.1</h1>

        <table>
            <tr>
                <td>

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
                </td>

                <td>
                    <h2>Rates</h2>
                    <livewire:rates-chart-component />
                </td>
            </tr>
        </table>

    </body>
</html>