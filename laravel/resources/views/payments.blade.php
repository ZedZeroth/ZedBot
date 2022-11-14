<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

    <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Payments</h1>

        @foreach($payments->sortByDesc('timestamp') as $payment)
            <p>
                {{ $payment->timestamp }}
                
                <a href='payment/{{ $payment->id }}'>
                    {{ $payment->formatAmount() }}
                    {{ $payment->currency->code }}
                </a>
            </p>
        @endforeach

    </body>
</html>