<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                {{ __('messages.financial_report') }}
            </h2>

            <a href="{{ route('reports.pdf', ['month' => $month]) }}"
                class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-green-500/30 transform hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                {{ __('messages.download_pdf') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Filter -->
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl border border-white/40 dark:border-gray-700 p-6">
            <form action="{{ route('reports.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
                <div class="w-full md:w-auto md:min-w-[250px]">
                    <label for="month" class="block font-bold text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('messages.select_month') }}</label>
                    <input type="month" name="month" id="month" value="{{ $month }}"
                        class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm sm:text-sm transition-all duration-300">
                </div>
                <div class="w-full md:w-auto">
                    <button type="submit"
                        class="w-full md:w-auto px-6 py-2.5 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 rounded-xl text-sm font-bold shadow-md hover:bg-gray-800 dark:hover:bg-white transition-colors flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        {{ __('messages.generate_report') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Income -->
            <div class="bg-gradient-to-br from-emerald-400 to-green-600 shadow-lg rounded-2xl border border-emerald-300 p-6 transition-transform hover:-translate-y-1 duration-300 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-20">
                    <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-bold text-emerald-100 uppercase tracking-wider">{{ __('messages.total_income') }}</p>
                    <p class="mt-2 text-4xl font-black tracking-tight">₹{{ number_format($totalIncome, 2) }}</p>
                    <div class="mt-4 flex items-center gap-2 text-sm font-medium text-emerald-100 bg-emerald-700/30 px-3 py-1.5 rounded-lg w-fit backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ __('messages.from_payments', ['count' => $payments->count()]) }}
                    </div>
                </div>
            </div>
            
            <!-- Total Expenses -->
            <div class="bg-gradient-to-br from-rose-400 to-red-600 shadow-lg rounded-2xl border border-rose-300 p-6 transition-transform hover:-translate-y-1 duration-300 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-20">
                    <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-bold text-rose-100 uppercase tracking-wider">{{ __('messages.total_expenses') }}</p>
                    <p class="mt-2 text-4xl font-black tracking-tight">₹{{ number_format($totalExpense, 2) }}</p>
                    <div class="mt-4 flex items-center gap-2 text-sm font-medium text-rose-100 bg-rose-700/30 px-3 py-1.5 rounded-lg w-fit backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        {{ __('messages.from_records', ['count' => $expenses->count()]) }}
                    </div>
                </div>
            </div>

            <!-- Net Profit/Loss -->
            <div class="{{ $netProfit >= 0 ? 'bg-gradient-to-br from-indigo-500 to-blue-600 border-indigo-400' : 'bg-gradient-to-br from-orange-400 to-red-500 border-orange-300' }} shadow-lg rounded-2xl border p-6 transition-transform hover:-translate-y-1 duration-300 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-20">
                    <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                </div>
                <div class="relative z-10">
                    <p class="text-sm font-bold text-white/80 uppercase tracking-wider">{{ __('messages.net_profit_loss') }}</p>
                    <p class="mt-2 text-4xl font-black tracking-tight">
                        {{ $netProfit >= 0 ? '+' : '' }}₹{{ number_format($netProfit, 2) }}
                    </p>
                    <div class="mt-4 flex items-center gap-2 text-sm font-medium text-white/90 bg-black/20 px-3 py-1.5 rounded-lg w-fit backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ __('messages.for_month', ['month' => $parsedMonth->format('M Y')]) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Payments (Income) -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl border border-white/40 dark:border-gray-700 overflow-hidden flex flex-col h-[500px]">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-emerald-50/50 dark:bg-emerald-900/10 flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.income_payments') }}</h3>
                </div>
                <div class="overflow-y-auto flex-1 custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.date') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.customer') }}</th>
                                <th class="px-5 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50 bg-transparent">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-emerald-50/50 dark:hover:bg-emerald-900/20 transition-colors">
                                    <td class="px-5 py-3 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        {{ $payment->payment_date->format('d M') }}
                                    </td>
                                    <td class="px-5 py-3 text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $payment->customer->name }}
                                    </td>
                                    <td class="px-5 py-3 text-sm font-black text-emerald-600 dark:text-emerald-400 text-right">
                                        + ₹{{ number_format($payment->amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-10 text-sm text-gray-500 text-center font-medium">
                                        {{ __('messages.no_income_for_month') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Expenses -->
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl border border-white/40 dark:border-gray-700 overflow-hidden flex flex-col h-[500px]">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-rose-50/50 dark:bg-rose-900/10 flex items-center gap-3">
                    <div class="p-2 bg-rose-100 dark:bg-rose-900/30 rounded-lg text-rose-600 dark:text-rose-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.expenses') }}</h3>
                </div>
                <div class="overflow-y-auto flex-1 custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.date') }}</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.description') }}</th>
                                <th class="px-5 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('messages.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50 bg-transparent">
                            @forelse($expenses as $expense)
                                <tr class="hover:bg-rose-50/50 dark:hover:bg-rose-900/20 transition-colors">
                                    <td class="px-5 py-3 text-sm font-medium text-gray-600 dark:text-gray-400">
                                        {{ $expense->expense_date->format('d M') }}
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1">{{ $expense->title }}</div>
                                        <div class="text-xs font-medium text-gray-500 mt-0.5">{{ $expense->category }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-sm font-black text-rose-600 dark:text-rose-400 text-right">
                                        - ₹{{ number_format($expense->amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-10 text-sm text-gray-500 text-center font-medium">
                                        {{ __('messages.no_expenses_for_month') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.3);
            border-radius: 20px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(75, 85, 99, 0.5);
        }
    </style>
</x-app-layout>