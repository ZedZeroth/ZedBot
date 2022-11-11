<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

    <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Currencies</h1>

        <ol>
            @foreach($currencies->sort() as $currency)
                <li>
                    <a href='currency/{{ $currency->code }}'>
                        {{ $currency->code }}
                        ({{ $currency->symbol }})
                    </a>
                </li>
            @endforeach
        </ol>

    </body>
</html>