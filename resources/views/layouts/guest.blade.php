<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PesatCare') }} - UKS SMK INFORMATIKA PESAT</title>

        <!-- Fonts Geist -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-100 min-h-screen selection:bg-[#20497B] selection:text-white flex flex-col justify-center">
        <!-- Centered Container -->
        <div class="w-full flex flex-col justify-center items-center px-4 py-8 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </body>
</html>
