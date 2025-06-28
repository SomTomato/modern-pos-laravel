<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern POS</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#e67e22"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <script>
        (function() {
            var theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
</head>
<body>
    <div class="app-container">
        <nav class="navbar">
            <div class="navbar-brand">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/SMS POS.png') }}" alt="SMS POS Logo" class="navbar-logo">
                    Modern POS
                </a>
            </div>
            <div class="navbar-user">
                @auth
                    <i class="fa-solid fa-user-circle"></i>
                    <span>Welcome, {{ Auth::user()->username }}!</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); this.closest('form').submit();"
                           class="btn btn-danger">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </a>
                    </form>
                @endauth
            </div>
        </nav>

        @include('layouts.sidebar')

        <main class="main-content">
            @include('partials.breadcrumbs')
            @include('partials.alerts')
            @yield('content')
        </main>

        <footer class="footer">
            <p>&copy; {{ date("Y") }} Modern POS. All Rights Reserved.</p>
        </footer>
    </div>

    {{-- FLOATING THEME-SWITCHER HTML --}}
    <div class="theme-switcher-fab">
        <button class="theme-btn" title="Change Theme"><i class="fa-solid fa-palette"></i></button>
        <ul class="dropdown-menu">
            <li>
                <a href="#" data-theme-value="light">
                    <span class="color-swatch" style="background-color: #e67e22;"></span>
                    Original Theme
                </a>
            </li>
            <li>
                <a href="#" data-theme-value="dark">
                    <span class="color-swatch" style="background-color: #bb86fc;"></span>
                    Dark Mode
                </a>
            </li>
            <li>
                <a href="#" data-theme-value="blue">
                    <span class="color-swatch" style="background-color: #3498db;"></span>
                    Blue Sky
                </a>
            </li>
        </ul>
    </div>

    @stack('scripts')

    {{-- SCRIPTS --}}
    <script>
        // PWA Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    console.log('ServiceWorker registration successful');
                }, err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }

        // Theme Switcher Logic
        document.addEventListener('DOMContentLoaded', function() {
            const themeSwitcher = document.querySelector('.theme-switcher-fab');
            const themeBtn = themeSwitcher.querySelector('.theme-btn');
            const themeLinks = themeSwitcher.querySelectorAll('.dropdown-menu a');
            
            // Toggle dropdown on button click
            themeBtn.addEventListener('click', function() {
                themeSwitcher.classList.toggle('active');
            });

            // Close dropdown if clicked outside
            document.addEventListener('click', function(e) {
                if (!themeSwitcher.contains(e.target)) {
                    themeSwitcher.classList.remove('active');
                }
            });

            // Handle theme selection
            themeLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const selectedTheme = this.dataset.themeValue;
                    document.documentElement.setAttribute('data-theme', selectedTheme);
                    localStorage.setItem('theme', selectedTheme);
                    themeSwitcher.classList.remove('active'); // Close menu after selection
                });
            });
        });
    </script>
</body>
</html>
