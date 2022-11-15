<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>

        <a href="/">🏠</a> &bull; <a href="/accounts">↩️</a>

        <h1>Account: {{ $account->identifier }}</h1>
        <h2>Network:
            <a href="/{{ $account->network }}/accounts">
                {{ $account->network }}
            </a>
        </h2>

        <h3>Holder: - </h3>

        <p>Network account name: {{ $account->networkAccountName }}</p>
        <p>Assumed account name: {{ $account->assumedAccountName }}</p>
        <p>Database ID: {{ $account->id }}</p>

        <span style="color: red;">
            <h3>Debits from this account</h3>

            @foreach($account->debits()->get() as $payment)
                <p>
                    {{ $payment->timestamp }}
                    <a href='/payment/{{ $payment->id }}'>
                        {{ $payment->memo }}</a>:
                        {{ $payment->formatAmount() }}
                        {{ $payment->currency->code }}
                </p>
            @endforeach
        </span>

        <span style="color: green;">
            <h3>Credits to this account</h3>

            @foreach($account->credits()->get() as $payment)
                <p>
                    {{ $payment->timestamp }}
                    <a href='/payment/{{ $payment->id }}'>
                        {{ $payment->memo }}</a>:
                        {{ $payment->formatAmount() }}
                        {{ $payment->currency->code }}
                </p>
            @endforeach
        </span>

    </body>
</html>