<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="..">Back</a>

        <h1>Currencies</h1>

        <ol>
            @foreach($currencies as $currency)
                <li>
                    <a href='currency/{{ $currency->code }}'>
                        {{ $currency->code }}
                    </a>
                </li>
            @endforeach
        </ol>

    </body>
</html>