<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/accounts/network/{{ $account->network }}">↩️</a>

        <h1>Account: {{ $account->identifier }}</h1>
        <h2>Network:
            <a href="/accounts/network/{{ $account->network }}">
                {{ $account->network }}
            </a>
        </h2>

        <h3>Holder: - </h3>

        <p>Database ID: {{ $account->id }}</p>

    </body>
</html>