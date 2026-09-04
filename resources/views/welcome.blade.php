<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Paper Boy') }} - Newspaper Distribution Management</title>

    <!-- PWA & Meta -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f46e5">
    <link rel="icon" href="{{ asset('icon-192x192.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .blob-1 {
            position: absolute;
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: linear-gradient(to right, #4f46e5, #ec4899);
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.4;
            animation: float 10s infinite ease-in-out alternate;
            z-index: -1;
        }
        .blob-2 {
            position: absolute;
            bottom: -10%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: linear-gradient(to right, #3b82f6, #8b5cf6);
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.3;
            animation: float 12s infinite ease-in-out alternate-reverse;
            z-index: -1;
        }
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 50px) scale(1.1); }
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-nav {
            background: rgba(17, 24, 39, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 dark:bg-gray-900 dark:text-gray-100 overflow-x-hidden relative selection:bg-indigo-500 selection:text-white" x-data="{ mobileMenuOpen: false }">
    
    <!-- Animated Background Blobs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="blob-1"></div>
        <div class="blob-2"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-blue-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    <span class="text-2xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300">
                        PaperBoy
                    </span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-bold text-gray-600 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 transition-colors">Features</a>
                    <a href="#pricing" class="text-sm font-bold text-gray-600 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 transition-colors">Pricing</a>
                    
                    <div class="h-6 w-px bg-gray-300 dark:bg-gray-700"></div>

                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 transition-colors">Go to Dashboard &rarr;</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400 transition-colors">Log in</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 dark:bg-white dark:hover:bg-gray-100 dark:text-gray-900 text-white rounded-xl text-sm font-bold shadow-lg transform hover:-translate-y-0.5 transition-all">
                            Get Started
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border-b border-gray-200 dark:border-gray-800" style="display: none;">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#features" @click="mobileMenuOpen = false" class="block px-3 py-3 text-base font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg">Features</a>
                <a href="#pricing" @click="mobileMenuOpen = false" class="block px-3 py-3 text-base font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg">Pricing</a>
                <div class="border-t border-gray-100 dark:border-gray-800 my-2"></div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="block px-3 py-3 text-base font-bold text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-3 text-base font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg">Log in</a>
                    <a href="{{ route('register') }}" class="block w-full text-center mt-2 px-5 py-3 bg-indigo-600 text-white rounded-xl text-base font-bold shadow-md">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 text-sm font-bold mb-8 border border-indigo-200 dark:border-indigo-800/50 shadow-sm">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                PaperBoy v2.0 is now live!
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black tracking-tight text-gray-900 dark:text-white mb-8 leading-tight">
                Manage your distribution <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">business seamlessly.</span>
            </h1>
            
            <p class="max-w-2xl mx-auto text-xl text-gray-600 dark:text-gray-400 mb-10 font-medium leading-relaxed">
                The all-in-one SaaS platform built specifically for newspaper distributors. Handle customers, automate billing, track collections, and grow your agency with ease.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-gray-900 hover:bg-gray-800 dark:bg-white dark:hover:bg-gray-100 dark:text-gray-900 text-white rounded-2xl text-lg font-black shadow-xl shadow-gray-900/20 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                    Start Your Free Trial
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
                <a href="#features" class="w-full sm:w-auto px-8 py-4 bg-white/50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800 backdrop-blur-md border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl text-lg font-bold shadow-sm transition-all text-center">
                    See How It Works
                </a>
            </div>
            
            <div class="mt-16 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                Trusted by 500+ distributors worldwide
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white/40 dark:bg-gray-900/40 backdrop-blur-3xl relative z-10 border-y border-white/50 dark:border-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-4">Everything you need to run your agency</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 font-medium">Say goodbye to messy spreadsheets and uncollected payments. PaperBoy automates the heavy lifting so you can focus on growth.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/40 dark:border-gray-700 p-8 rounded-3xl shadow-xl shadow-gray-200/40 dark:shadow-black/20 hover:-translate-y-1 transition-transform">
                    <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Customer Management</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed font-medium">Track customer details, active subscriptions, and temporary leaves (vacation pauses) all in one place.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/40 dark:border-gray-700 p-8 rounded-3xl shadow-xl shadow-gray-200/40 dark:shadow-black/20 hover:-translate-y-1 transition-transform">
                    <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Automated Billing</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed font-medium">Generate monthly invoices automatically. The system calculates prorated amounts for customer leaves instantly.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/40 dark:border-gray-700 p-8 rounded-3xl shadow-xl shadow-gray-200/40 dark:shadow-black/20 hover:-translate-y-1 transition-transform">
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">WhatsApp Integration</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed font-medium">Send invoices and payment reminders directly to your customers' WhatsApp with a single click.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/40 dark:border-gray-700 p-8 rounded-3xl shadow-xl shadow-gray-200/40 dark:shadow-black/20 hover:-translate-y-1 transition-transform">
                    <div class="w-14 h-14 bg-orange-100 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Daily Collections</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed font-medium">Equip your delivery staff with a mobile-friendly interface to log daily cash collections on the go.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/40 dark:border-gray-700 p-8 rounded-3xl shadow-xl shadow-gray-200/40 dark:shadow-black/20 hover:-translate-y-1 transition-transform">
                    <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Vendor Management</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed font-medium">Keep track of daily newspaper purchases from publishers and settle your vendor accounts monthly.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl border border-white/40 dark:border-gray-700 p-8 rounded-3xl shadow-xl shadow-gray-200/40 dark:shadow-black/20 hover:-translate-y-1 transition-transform">
                    <div class="w-14 h-14 bg-cyan-100 dark:bg-cyan-900/40 text-cyan-600 dark:text-cyan-400 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Insightful Reports</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed font-medium">Generate comprehensive reports on collections, outstanding balances, and monthly profits to stay on top of your business.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-4">Simple, transparent pricing</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 font-medium">Choose the plan that fits your agency size. No hidden fees, ever.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto justify-center">
                @forelse($plans as $plan)
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border {{ $loop->iteration == 2 ? 'border-indigo-500 shadow-2xl shadow-indigo-500/20 scale-105 z-10' : 'border-gray-200 dark:border-gray-700 shadow-xl shadow-gray-200/50 dark:shadow-black/20' }} rounded-3xl p-8 flex flex-col transition-transform hover:-translate-y-2 relative">
                        
                        @if($loop->iteration == 2)
                            <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <span class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">Most Popular</span>
                            </div>
                        @endif

                        <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">{{ $plan->name }}</h3>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-6">{{ $plan->description ?? 'Perfect for ' . $plan->name . ' agencies.' }}</p>
                        
                        <div class="mb-6 flex items-baseline gap-1 border-b border-gray-100 dark:border-gray-700 pb-6">
                            <span class="text-4xl font-black text-gray-900 dark:text-white">₹{{ number_format($plan->price, 0) }}</span>
                            <span class="text-gray-500 font-bold">/ {{ $plan->billing_interval }}</span>
                        </div>

                        <ul class="space-y-4 mb-8 flex-1">
                            <li class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-indigo-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $plan->customer_limit ? number_format($plan->customer_limit) . ' Customers' : 'Unlimited Customers' }}
                                </span>
                            </li>
                            @if(is_array($plan->features))
                                @foreach($plan->features as $feature)
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-indigo-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ trim($feature) }}</span>
                                    </li>
                                @endforeach
                            @endif
                        </ul>

                        <a href="{{ route('register', ['plan' => $plan->slug]) }}" class="block w-full py-3 px-4 text-center rounded-xl font-bold transition-all {{ $loop->iteration == 2 ? 'bg-gray-900 hover:bg-gray-800 dark:bg-white dark:hover:bg-gray-100 dark:text-gray-900 text-white shadow-lg' : 'bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 dark:text-indigo-300' }}">
                            {{ $plan->trial_days ? 'Start ' . $plan->trial_days . '-Day Free Trial' : 'Get Started' }}
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center p-12 bg-white/50 dark:bg-gray-800/50 backdrop-blur-md rounded-3xl border border-gray-200 dark:border-gray-700">
                        <p class="text-xl font-bold text-gray-500">Pricing plans are currently being updated.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white/80 dark:bg-gray-950/80 backdrop-blur-xl border-t border-gray-200 dark:border-gray-800 relative z-10 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    <span class="text-xl font-black text-gray-900 dark:text-white">PaperBoy</span>
                </div>
                <div class="flex gap-6 text-sm font-bold text-gray-500 dark:text-gray-400">
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Contact</a>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-800 text-center text-sm font-medium text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name', 'PaperBoy') }}. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
