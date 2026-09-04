<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            {{ __('messages.dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Plan Usage Banner -->
        <div class="bg-gradient-to-r from-blue-500/10 to-purple-500/10 dark:from-blue-900/30 dark:to-purple-900/30 backdrop-blur-xl border border-white/40 dark:border-white/10 rounded-2xl shadow-xl shadow-blue-500/5 overflow-hidden p-6 relative group transition-all duration-300 hover:shadow-blue-500/10">
            <div class="absolute inset-0 bg-white/20 dark:bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex-1 w-full">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            {{ __('messages.customer_quota_usage') }}
                        </h3>
                        <span class="px-3 py-1 bg-white/50 dark:bg-gray-800/50 rounded-full text-sm font-semibold {{ $usedCustomers >= $customerLimit ? 'text-red-500' : 'text-blue-600 dark:text-blue-400' }} shadow-sm">
                            {{ $usedCustomers }} / {{ $customerLimit === PHP_INT_MAX ? __('messages.unlimited') : $customerLimit }}
                        </span>
                    </div>
                    @php 
                        $percent = $customerLimit === PHP_INT_MAX ? 0 : min(100, round(($usedCustomers / max(1, $customerLimit)) * 100)); 
                        $color = $percent >= 100 ? 'from-red-500 to-rose-600' : ($percent >= 80 ? 'from-yellow-400 to-orange-500' : 'from-blue-400 to-indigo-500');
                    @endphp
                    <div class="w-full bg-gray-200/50 dark:bg-gray-700/50 rounded-full h-3 backdrop-blur-sm overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r {{ $color }} transition-all duration-1000 ease-out" style="width: {{ $percent }}%"></div>
                    </div>
                    @if($percent >= 100)
                        <p class="mt-3 text-sm font-medium text-red-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ __('messages.reached_limit_msg') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Customers -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl border border-white/40 dark:border-gray-700 shadow-lg shadow-gray-200/50 dark:shadow-black/20 p-6 transform hover:-translate-y-1.5 transition-all duration-300 hover:shadow-indigo-500/20 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('messages.total_customers') }}</p>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $totalCustomers }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Subscriptions -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl border border-white/40 dark:border-gray-700 shadow-lg shadow-gray-200/50 dark:shadow-black/20 p-6 transform hover:-translate-y-1.5 transition-all duration-300 hover:shadow-emerald-500/20 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('messages.active_subscriptions') }}</p>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $totalSubscriptions }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl border border-white/40 dark:border-gray-700 shadow-lg shadow-gray-200/50 dark:shadow-black/20 p-6 transform hover:-translate-y-1.5 transition-all duration-300 hover:shadow-blue-500/20 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('messages.monthly_revenue') }}</p>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white">₹{{ number_format($monthlyRevenue, 2) }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Outstanding -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl border border-white/40 dark:border-gray-700 shadow-lg shadow-gray-200/50 dark:shadow-black/20 p-6 transform hover:-translate-y-1.5 transition-all duration-300 hover:shadow-rose-500/20 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('messages.unpaid_invoices') }}</p>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white">₹{{ number_format($unpaidInvoices, 2) }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 text-white shadow-lg shadow-rose-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Financial Summary -->
            <div class="lg:col-span-1 bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl p-6 border border-white/40 dark:border-gray-700 flex flex-col">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">{{ __('messages.this_months_summary') }}</h3>
                <div class="space-y-5 flex-1">
                    <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800/30">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-green-700 dark:text-green-400">{{ __('messages.total_revenue_received') }}</span>
                            <span class="font-bold text-green-700 dark:text-green-400">₹{{ number_format($monthlyRevenue, 2) }}</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/30">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-red-700 dark:text-red-400">{{ __('messages.total_expenses') }}</span>
                            <span class="font-bold text-red-700 dark:text-red-400">₹{{ number_format($monthlyExpenses, 2) }}</span>
                        </div>
                    </div>
                    
                    @php $net = $monthlyRevenue - $monthlyExpenses; @endphp
                    <div class="p-5 rounded-xl border {{ $net >= 0 ? 'bg-gradient-to-br from-green-500/10 to-emerald-500/10 border-green-200 dark:border-green-700' : 'bg-gradient-to-br from-red-500/10 to-rose-500/10 border-red-200 dark:border-red-700' }} mt-4">
                        <div class="flex flex-col items-center justify-center text-center">
                            <span class="text-sm font-semibold uppercase tracking-wider mb-1 {{ $net >= 0 ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">{{ __('messages.net_profit') }}</span>
                            <span class="text-3xl font-black tracking-tight {{ $net >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                ₹{{ number_format($net, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('expenses.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-primary bg-primary/10 hover:bg-primary/20 dark:hover:bg-primary/30 transition-colors w-full">
                        {!! __('messages.view_detailed_expenses') !!}
                    </a>
                </div>
            </div>

            <!-- Recent Invoices & Payments -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Invoices Table -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl border border-white/40 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            {{ __('messages.recent_invoices') }}
                        </h3>
                        <a href="{{ route('invoices.index') }}" class="text-sm font-semibold text-primary hover:text-primary-dark transition-colors">{{ __('messages.view_all') }} &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                            <thead class="bg-white/50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.invoice_hash') }}</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.customer') }}</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.amount') }}</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-transparent">
                                @forelse($recentInvoices as $invoice)
                                    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/80 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">
                                            <a href="{{ route('invoices.show', $invoice->id) }}" class="text-primary hover:underline">#{{ $invoice->invoice_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 font-medium">
                                            {{ $invoice->customer->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-bold">
                                            ₹{{ number_format($invoice->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full 
                                                {{ $invoice->status === 'Paid' ? 'bg-green-100/80 text-green-700 dark:bg-green-900/50 dark:text-green-400 border border-green-200 dark:border-green-800' :
                                                ($invoice->status === 'Partially Paid' ? 'bg-yellow-100/80 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800' : 
                                                'bg-red-100/80 text-red-700 dark:bg-red-900/50 dark:text-red-400 border border-red-200 dark:border-red-800') }}">
                                                {{ $invoice->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <p>{{ __('messages.no_invoices_found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl border border-white/40 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ __('messages.recent_payments') }}
                        </h3>
                    </div>
                    <ul role="list" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentPayments as $payment)
                            <li class="p-4 hover:bg-gray-50/80 dark:hover:bg-gray-800/80 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-bold border border-emerald-200 dark:border-emerald-800">
                                            {{ substr($payment->customer->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                            {{ $payment->customer->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate flex items-center gap-1 mt-0.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $payment->payment_date->diffForHumans() }} {{ __('messages.via') }} <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $payment->payment_mode }}</span>
                                        </p>
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-black text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 shadow-sm">
                                            +₹{{ number_format($payment->amount, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="p-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    <p>{{ __('messages.no_recent_payments') }}</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                    <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 text-center">
                        <a href="{{ route('payments.index') }}" class="text-sm font-semibold text-primary hover:text-primary-dark transition-colors inline-flex items-center gap-1">
                            {!! __('messages.view_all_payments') !!}
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>