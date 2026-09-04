<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 Server Error - {{ config('app.name', 'PaperBoy') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans antialiased text-gray-900 dark:text-gray-100 min-h-screen flex items-center justify-center">
    <div class="text-center px-6 py-12">
        <h1 class="text-9xl font-extrabold text-red-500 mb-4">500</h1>
        <h2 class="text-3xl font-bold mb-4">Internal Server Error</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
            Oops! Something went wrong on our end. We're looking into it. Please try again later.
        </p>
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary-dark transition shadow-lg">
            Return to Dashboard
        </a>
    </div>
</body>
</html>
