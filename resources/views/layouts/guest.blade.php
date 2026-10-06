<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PesatCare') }}</title>

        <!-- Fonts Geist -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-800 antialiased bg-slate-50 relative min-h-screen selection:bg-[#3368A0] selection:text-white">
        <!-- Ambient Medical Gradient Accents -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-[#C8DFDB]/40 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-[#66A3BF]/20 blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[radial-gradient(#3368A0_0.75px,transparent_0.75px)] [background-size:24px_24px] opacity-[0.03]"></div>
        </div>

        <!-- Centered Container -->
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </body>
</html>