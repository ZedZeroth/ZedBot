<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/">↩️</a>

        <h1>Accounts on every network</h1>

        <a href='/account/networks'>
            View accounts by network
        </a>

        <ul>
        @foreach($accounts as $account)
            <li>                
                <a href="/account/{{ $account->identifier }}">
                    {{ $account->identifier }}
                </a>
                <span style="font-weight: bold;">
                    {{ $account->networkAccountName }}
                </span>
                ({{ $account->label }})
            </li>
        @endforeach
        </ul>

    </body>
</html>