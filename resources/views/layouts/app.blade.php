<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PrepToEat') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-bx1EyQ2XDT5DMr0YJIUKGwEuITdSb9VjA36TObgGJE0E7E5Wdl66iRS0LlwM651c01qmPvvrL1jAUx0vYB6NWg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')

    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="font-sans antialiased text-slate-900 bg-gradient-to-b from-emerald-50 via-white to-white">
    <div class="flex min-h-screen flex-col">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white/80 backdrop-blur border-b border-emerald-100/70">
                <div class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1">
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>

    @stack('scripts')
</body>
</html>
