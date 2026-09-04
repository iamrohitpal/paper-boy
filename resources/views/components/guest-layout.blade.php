<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        // Fail gracefully if settings table isn't migrated yet (e.g., initial setup)
        try {
            $settings = \App\Models\Setting::first();
            $appName = $settings->app_name ?? config('app.name', 'Newspaper Distributor');
            $primaryColor = $settings->primary_color ?? '#4f46e5';
        } catch (\Exception $e) {
            $appName = config('app.name', 'Newspaper Distributor');
            $primaryColor = '#4f46e5';
        }
    @endphp

    <title>{{ $appName }}</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="{{ $primaryColor }}">
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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

<body class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex flex-col md:flex-row bg-[#1B2430] text-gray-200">

        <!-- Left Side: Image Area (Hidden on mobile) -->
        <div class="hidden md:flex md:w-1/2 relative items-center justify-center overflow-hidden bg-cover bg-center" style="background-image: url('{{ request()->getBaseUrl() }}/images/auth-hero.png');">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black opacity-40"></div>

            <div class="relative z-10 text-center px-8">
                <h1 class="text-5xl font-bold text-white mb-4">Welcome Back</h1>
                <p class="text-xl text-white mb-10">Manage your Paper Boy business with ease</p>

                <ul class="space-y-4 text-white text-lg">
                    <li>Manage Customers & Subscriptions</li>
                    <li>Track Collections & Pending Bills</li>
                    <li>Generate Monthly Invoices</li>
                </ul>
            </div>
        </div>

        <!-- Right Side: Form Container -->
        <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-8 relative z-10">
            <div class="w-full max-w-sm">
                <!-- Logo / App Name -->
                <div class="mb-10 text-center">
                    <a href="/" class="inline-block">
                        @if(isset($settings) && $settings && $settings->logo_path)
                            <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-16 mx-auto">
                        @else
                            <h1 class="text-4xl font-bold tracking-tight text-[#00AEEF] uppercase">{{ $appName }}</h1>
                        @endif
                    </a>
                    <p class="mt-2 text-gray-400 text-sm">Admin Control Panel</p>
                </div>

                <!-- Slot Content -->
                <div class="w-full">
                    {{ $slot }}
                </div>

                <div class="mt-16 text-center">
                    <p class="text-xs text-gray-500">© {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
                </div>
            </div>
        </div>

    </div>
</body>

</html>