<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/payment/networks">↩️</a>

        <h1>Payments on the {{ $network }} Network</h1>

        <ul>
        @foreach($paymentsOnNetwork as $payment)
            <li>
                {{ $payment->timestamp }}
                
                <a href='/payment/{{ $payment->id }}'>
                    {{ $payment->memo }}:
                    {{ $payment->formatAmount() }}
                    {{ $payment->currency->code }}
                </a>
            </li>
        @endforeach
        </ul>

    </body>
</html>