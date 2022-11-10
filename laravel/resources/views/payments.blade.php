<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

    <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Payments</h1>

        @foreach($payments as $payment)
            <p>
                {{ $payment->timestamp }}
                
                <a href='payment/{{ $payment->id }}'>
                    {{ $payment->amount }}
                    {{ $payment->currency }}
                </a>
            </p>
        @endforeach

    </body>
</html>