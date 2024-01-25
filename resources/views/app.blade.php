<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- Scripts --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Template Main CSS File -->
            <!-- Styles -->
            <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
            <link href="assets/css/bootstrap.min.css" rel="stylesheet">
            <link href="assets/css/fontawesome-all.min.css" rel="stylesheet">
            {{-- <link href="assets/css/swiper.css" rel="stylesheet"> --}}
            {{-- <link href="assets/css/styles.css" rel="stylesheet">
             --}}
            <!-- Scripts -->
            <script src="assets/js/bootstrap.min.js"></script> <!-- Bootstrap framework -->
            {{-- <script src="assets/js/swiper.min.js"></script> <!-- Swiper for image and text sliders --> --}}
            {{-- <script src="assets/js/scripts.js"></script> <!-- Custom scripts -->              
    </head> --}}
    <body>
        <div id="app">
            <router-view />
        </div>
    </body>
</html>