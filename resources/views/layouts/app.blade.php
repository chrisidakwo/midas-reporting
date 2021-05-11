<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Dashboard') | MIDAS Reporting Portal</title>

    <link rel="stylesheet" href="{{ asset('fonts/Inter/inter.css') }}">

    <link rel="stylesheet" href="{{ asset('css/material-icons.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <livewire:styles />

    @stack('css')
</head>
<body class="antialiased">
    <div class="wrapper">
        @include('partials.header')

        <main class="content">
            @include('partials.breadcrumb')

            <div class="container-fluid">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <livewire:scripts />

    @stack('js')
</body>
</html>
