<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Financial Report') }}
            </h2>

            <a href="{{ route('reports.pdf', ['month' => $month]) }}"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium shadow flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download PDF
            </a>
        </div>
    </x-slot>

    <div>
        <!-- Filter -->
        <div
            class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <form action="{{ route('reports.index') }}" method="GET"
                class="flex flex-col md:flex-row md:items-end gap-4">
                <div>
                    <label for="month" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Select
                        Month</label>
                    <input type="month" name="month" id="month" value="{{ $month }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                </div>
                <div>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium shadow">
                        Generate Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Income
                    </p>
                    <p class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">
                        ₹{{ number_format($totalIncome, 2) }}</p>
                    <p class="mt-1 text-sm text-gray-500">From {{ $payments->count() }} payments</p>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total
                        Expenses</p>
                    <p class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">
                        ₹{{ number_format($totalExpense, 2) }}</p>
                    <p class="mt-1 text-sm text-gray-500">From {{ $expenses->count() }} records</p>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Net
                        Profit/Loss</p>
                    <p
                        class="mt-2 text-3xl font-bold {{ $netProfit >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-red-600 dark:text-red-400' }}">
                        ₹{{ number_format($netProfit, 2) }}
                    </p>
                    <p class="mt-1 text-sm text-gray-500">For {{ $parsedMonth->format('F Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Payments (Income) -->
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Income (Payments)</h3>
                </div>
                <div class="overflow-y-auto max-h-96">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-white dark:bg-gray-800 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Customer
                                </th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($payments as $payment)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $payment->payment_date->format('d M') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">
                                        {{ $payment->customer->name }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-green-600 dark:text-green-400 text-right font-medium">
                                        ₹{{ number_format($payment->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-sm text-gray-500 text-center">No income for this
                                        month.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Expenses -->
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Expenses</h3>
                </div>
                <div class="overflow-y-auto max-h-96">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-white dark:bg-gray-800 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description
                                </th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($expenses as $expense)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $expense->expense_date->format('d M') }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">
                                        {{ $expense->title }} <br>
                                        <span class="text-xs text-gray-500">{{ $expense->category }}</span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-red-600 dark:text-red-400 text-right font-medium">
                                        ₹{{ number_format($expense->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-sm text-gray-500 text-center">No expenses for this
                                        month.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>