<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HRMO') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

         <!-- Scripts -->
         @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/javaclock.js'])

    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#1F2937] dark:bg-gray-900">
            @include('layouts.nav2')
            
            <div class="dark:bg-gray-800 mt-6 overflow-hidden px-6 py-4 shadow-md sm:rounded-lg">
                {{ $time }} {{ $picture }}
            </div>
            
        </div>
        
    </body>
</html>