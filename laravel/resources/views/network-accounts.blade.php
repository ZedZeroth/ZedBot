<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

    <a href="/">🏠</a> &bull; <a href="/account/networks">↩️</a>

        <h1>Accounts on the {{ $network }} Network</h1>

        <ul>
        @foreach($accountsOnNetwork as $account)
            <li>                
                <a href="/account/{{ $account->identifier }}">
                    {{ $account->identifier }}
                </a>
            </li>
        @endforeach
        </ul>

    </body>
</html>