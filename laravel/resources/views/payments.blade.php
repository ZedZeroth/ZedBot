<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Payments on every network</h1>

        <a href='/payment/networks'>
            View payments by network
        </a>

        <ul>
        @foreach($payments as $payment)
            <li>
                {{ $payment->timestamp }}
                [{{ $payment->network }}]
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