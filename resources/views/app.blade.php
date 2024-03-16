<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- get title from vite vue router --}}
        <title>Laravel</title>
        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- Scripts --}}
        @vite('resources/js/app.js')
        <!-- Template Main CSS File -->
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
   </head>
    <body>
        <div id="app">
            <router-view />
        </div>
    </body>
</html>