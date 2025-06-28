<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body>
        <div class="login-container">
            <div class="login-box">
                <!-- Left Side: Logo and Slogan -->
                <div class="login-promo">
                    {{-- THE FIX: Instead of a variable, we directly include the logo component --}}
                    <a href="/">
                        <x-application-logo />
                    </a>
                    <p class="login-slogan">Your Product is Safe With Us</p>
                </div>

                <!-- Right Side: Login/Register Form -->
                <div class="login-form-container">
                    {{-- This $slot variable contains the login or register form fields --}}
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
    