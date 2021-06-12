<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Dashboard') | MIDAS Reporting Portal</title>

        <link rel="stylesheet" href="{{ asset('fonts/Inter/inter.css') }}">
        <link rel="stylesheet" href="{{ asset('css/material-icons.css') }}">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>

    <body class="antialiased">
        @inertia
    </body>
</html>
