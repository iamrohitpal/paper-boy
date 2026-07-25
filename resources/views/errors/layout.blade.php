<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name', 'Paper Boy') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .error-bg {
            background-color: #0f172a;
            background-image: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
        }
        .error-code {
            text-shadow: 0px 4px 20px rgba(0, 174, 239, 0.4);
        }
    </style>
</head>
<body class="antialiased error-bg min-h-screen flex items-center justify-center font-sans text-gray-200">
    <div class="max-w-2xl w-full px-6">
        <div class="text-center">
            
            <div class="mb-8">
                <a href="{{ url('/') }}" class="inline-block">
                    <h1 class="text-3xl font-bold tracking-tight text-[#00AEEF] uppercase">
                        @php
                            try {
                                $appName = \App\Models\Setting::first()->app_name ?? config('app.name', 'Paper Boy');
                            } catch (\Exception $e) {
                                $appName = config('app.name', 'Paper Boy');
                            }
                        @endphp
                        {{ $appName }}
                    </h1>
                </a>
            </div>

            <!-- Error Content -->
            <div class="bg-gray-800/50 backdrop-blur-md rounded-2xl p-10 border border-gray-700 shadow-2xl relative overflow-hidden group">
                <!-- Decorative glow -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-[#00AEEF] rounded-full blur-[80px] opacity-20 group-hover:opacity-30 transition-opacity duration-500"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-[#00AEEF] rounded-full blur-[80px] opacity-20 group-hover:opacity-30 transition-opacity duration-500"></div>

                <div class="relative z-10">
                    <h1 class="text-8xl font-black text-white tracking-tighter error-code mb-4">
                        @yield('code')
                    </h1>
                    
                    <h2 class="text-2xl md:text-3xl font-semibold text-gray-100 mb-4">
                        @yield('message')
                    </h2>
                    
                    <p class="text-gray-400 mb-8 max-w-md mx-auto">
                        @yield('description', 'Something went wrong. Please return to the dashboard or contact support if the issue persists.')
                    </p>

                    <div class="flex justify-center space-x-4 flex-wrap gap-y-4">
                        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" class="px-6 py-3 rounded-lg font-medium text-white bg-gray-700 hover:bg-gray-600 transition-colors shadow-lg shadow-gray-900/20">
                            Go Back
                        </a>
                        <a href="{{ url('/dashboard') }}" class="px-6 py-3 rounded-lg font-medium text-white bg-[#00AEEF] hover:bg-[#0096D1] transition-colors shadow-lg shadow-[#00AEEF]/20">
                            Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
