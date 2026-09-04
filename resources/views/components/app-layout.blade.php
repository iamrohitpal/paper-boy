<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $settings = \App\Models\Setting::first();
        $appName = $settings->app_name ?? config('app.name', 'Newspaper Distributor');
        $primaryColor = $settings->primary_color ?? '#4f46e5'; // Default indigo-600
    @endphp

    <title>{{ $appName }}</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="{{ $primaryColor }}">
    
    <!-- iOS Support -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $appName }}">
    
    @if(isset($settings) && $settings->logo_path)
        <link rel="icon" href="{{ Storage::url($settings->logo_path) }}">
        <link rel="apple-touch-icon" href="{{ Storage::url($settings->logo_path) }}">
    @else
        <link rel="icon" href="{{ asset('icon-192x192.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('icon-192x192.png') }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Dynamic Colors -->
    <style>
        :root {
            --color-primary:
                {{ $primaryColor }}
            ;
        }

        .bg-primary {
            background-color: var(--color-primary) !important;
        }

        .text-primary {
            color: var(--color-primary) !important;
        }

        .border-primary {
            border-color: var(--color-primary) !important;
        }

        .hover\:bg-primary-dark:hover {
            filter: brightness(0.9);
            background-color: var(--color-primary) !important;
        }

        .focus\:ring-primary:focus {
            --tw-ring-color: var(--color-primary) !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register("{{ asset('sw.js') }}")
                    .then(registration => {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch(err => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 transition-colors duration-200"
    x-data="{ sidebarOpen: false }">
    <div class="flex flex-col h-screen overflow-hidden">
        <!-- Top Navbar (Full Width) -->
        <x-navbar />

        <div class="flex flex-1 overflow-hidden">
            <!-- Mobile sidebar backdrop -->
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-80 lg:hidden" @click="sidebarOpen = false" style="display: none;"></div>

            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main Content -->
            <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

                <!-- Page Header -->
                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow transition-colors duration-200">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="p-2 sm:p-4 lg:p-6 w-full max-w-full overflow-x-hidden">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
    
    <x-confirm-modal />
</body>

</html>