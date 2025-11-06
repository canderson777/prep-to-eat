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

    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="font-sans antialiased text-slate-900 bg-gradient-to-b from-emerald-50 via-white to-white">
    <div class="flex min-h-screen flex-col">
        @include('layouts.navigation')

        <main class="flex-1">
            <div class="mx-auto flex w-full max-w-7xl flex-col items-center px-4 py-12 sm:px-6 lg:px-8">
                <div class="w-full max-w-md rounded-2xl bg-white/80 p-8 shadow-xl ring-1 ring-emerald-100/80 backdrop-blur">
                    <div class="mb-6 flex items-center gap-3 text-emerald-700">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100">
                            <i class="fa-solid fa-bowl-food"></i>
                        </span>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-500">Welcome to PrepToEat</p>
                            <h1 class="text-lg font-semibold text-emerald-900">Fuel your kitchen journey</h1>
                        </div>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </main>

        @include('layouts.footer')
    </div>
</body>
</html>
