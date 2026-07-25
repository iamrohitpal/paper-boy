<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Customers -->
            <div
                class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700 transition-all hover:shadow-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Total Active Customers</p>
                        <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $totalCustomers }}</p>
                    </div>
                </div>
            </div>

            <!-- Subscriptions -->
            <div
                class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700 transition-all hover:shadow-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Active Subscriptions</p>
                        <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $totalSubscriptions }}</p>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div
                class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700 transition-all hover:shadow-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Monthly Revenue</p>
                        <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                            ₹{{ number_format($monthlyRevenue, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Outstanding -->
            <div
                class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700 transition-all hover:shadow-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center">
                    <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 text-red-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Unpaid Invoices</p>
                        <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                            ₹{{ number_format($unpaidInvoices, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Profit/Loss -->
            <div
                class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">This Month's Summary</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Total Revenue Received</span>
                        <span
                            class="font-bold text-green-600 dark:text-green-400">₹{{ number_format($monthlyRevenue, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Total Expenses</span>
                        <span
                            class="font-bold text-red-600 dark:text-red-400">₹{{ number_format($monthlyExpenses, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-lg font-semibold text-gray-800 dark:text-gray-200">Net Profit</span>
                        @php $net = $monthlyRevenue - $monthlyExpenses; @endphp
                        <span
                            class="text-xl font-bold {{ $net >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            ₹{{ number_format($net, 2) }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('expenses.index') }}"
                        class="text-primary hover:text-primary-dark font-medium transition-colors">View Detailed
                        Expenses &rarr;</a>
                </div>
            </div>

            <!-- Recent Payments -->
            <div
                class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Payments</h3>

                <div class="flow-root">
                    <ul role="list" class="-my-5 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentPayments as $payment)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $payment->customer->name }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ $payment->payment_date->diffForHumans() }} via {{ $payment->payment_mode }}
                                        </p>
                                    </div>
                                    <div>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            +₹{{ number_format($payment->amount, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No recent payments.
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="mt-4 text-center border-t border-gray-200 dark:border-gray-700 pt-4">
                    <a href="{{ route('payments.index') }}"
                        class="text-primary hover:text-primary-dark font-medium transition-colors">View All Payments
                        &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Recent Invoices Table -->
        <div
            class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm shadow-sm rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 mt-8">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Recent Invoices</h3>
                <a href="{{ route('invoices.index') }}"
                    class="text-primary hover:text-primary-dark font-medium transition-colors">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Invoice #</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Customer</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Amount</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentInvoices as $invoice)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                    <a href="{{ route('invoices.show', $invoice->id) }}"
                                                        class="text-primary hover:text-primary-dark">{{ $invoice->invoice_number }}</a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $invoice->customer->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                    ₹{{ number_format($invoice->total_amount, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                                                        {{ $invoice->status === 'Paid' ? 'bg-green-100 text-green-800' :
                            ($invoice->status === 'Partially Paid' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ $invoice->status }}
                                                    </span>
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    No invoices found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>