<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Vendor Purchases') }}
            </h2>
            <a href="{{ route('purchases.create') }}"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium shadow transition-colors">
                + Record Purchase
            </a>
        </div>
    </x-slot>

    <!-- Filter and Summaries -->
    <div class="space-y-6" x-data="{ showSettleModal: false, settlePublisher: '', settleAmount: 0, settleMonth: '{{ $month }}' }">

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Purchased
                    ({{ \Carbon\Carbon::parse($month)->format('M Y') }})</h3>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    ₹{{ number_format($totalPurchased, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Paid</h3>
                <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">
                    ₹{{ number_format($totalPaid, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Opening Balance (Arrears)</h3>
                <p class="mt-1 text-2xl font-bold {{ $openingBalance > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                    ₹{{ number_format($openingBalance, 2) }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Month's Net Balance</h3>
                <p class="mt-1 text-2xl font-bold {{ $currentMonthBalance > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                    ₹{{ number_format($currentMonthBalance, 2) }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Outstanding Balance</h3>
                <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">₹{{ number_format($totalBalance, 2) }}
                </p>
            </div>
        </div>

        <!-- Outstanding By Publisher (Only if there are balances) -->
        @if($publisherBalances->isNotEmpty())
            <div
                class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Outstanding Balances to Distributors
                    </h3>
                </div>
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($publisherBalances as $balance)
                        <div
                            class="flex flex-col p-3 bg-red-50 dark:bg-red-900/20 rounded-md border border-red-100 dark:border-red-800">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $balance->publisher }}</span>
                                <span
                                    class="text-sm font-bold text-red-600 dark:text-red-400">₹{{ number_format($balance->total_due, 2) }}</span>
                            </div>
                            <div class="mt-2 text-right">
                                <button type="button" @click="showSettleModal = true; settlePublisher = '{{ addslashes($balance->publisher) }}'; settleAmount = {{ $balance->total_due }};" class="px-2 py-1 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700 transition-colors shadow-sm">
                                    Settle
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Filter Form -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 border border-gray-100 dark:border-gray-700">
            <form action="{{ route('purchases.index') }}" method="GET"
                class="flex flex-col sm:flex-row gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Month</label>
                    <input type="month" name="month" value="{{ $month }}"
                        class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Newspaper</label>
                    <select name="newspaper_id"
                        class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm sm:text-sm">
                        <option value="">All Newspapers</option>
                        @foreach($newspapers as $np)
                            <option value="{{ $np->id }}" {{ $newspaper_id == $np->id ? 'selected' : '' }}>{{ $np->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('purchases.index') }}"
                        class="ml-2 px-4 py-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Clear</a>
                </div>
            </form>
        </div>

        <!-- Purchases Table -->
        <div
            class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Date</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Newspaper</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Qty @ Rate</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Paid</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Balance</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($purchases as $purchase)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $purchase->purchase_date->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $purchase->newspaper->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $purchase->newspaper->publisher }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div>{{ $purchase->quantity }} @ ₹{{ number_format($purchase->rate, 2) }}</div>
                                    @if($purchase->return_quantity > 0)
                                        <div class="text-xs text-red-500 mt-1">Ret: {{ $purchase->return_quantity }} @ ₹{{ number_format($purchase->return_rate, 2) }}</div>
                                    @endif
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                    ₹{{ number_format($purchase->total_amount, 2) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 font-medium">
                                    ₹{{ number_format($purchase->amount_paid, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($purchase->balance_due > 0)
                                        <span
                                            class="text-sm font-bold text-red-600 dark:text-red-400">₹{{ number_format($purchase->balance_due, 2) }}</span>
                                    @elseif($purchase->balance_due < 0)
                                        <span class="text-sm font-bold text-blue-600 dark:text-blue-400">₹{{ number_format(abs($purchase->balance_due), 2) }} (Cr)</span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Settled</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                                        class="inline-block"
                                        x-data @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this record?', onConfirm: () => $el.submit() })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    No purchases found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($purchases->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    {{ $purchases->appends(['month' => $month, 'newspaper_id' => $newspaper_id])->links() }}
                </div>
            @endif

        <!-- Settlement Modal -->
        <div x-show="showSettleModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showSettleModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showSettleModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showSettleModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form action="{{ route('purchases.settle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="month" x-model="settleMonth">
                        <input type="hidden" name="publisher" x-model="settlePublisher">
                        
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                        Settle Account: <span x-text="settlePublisher"></span>
                                    </h3>
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount to Settle</label>
                                        <p class="mt-1 text-2xl font-bold text-red-600 dark:text-red-400">₹<span x-text="Number(settleAmount).toFixed(2)"></span></p>
                                    </div>
                                    <div class="mt-4">
                                        <label for="amount_paid" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount to Pay Today</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm">₹</span>
                                            </div>
                                            <input type="number" step="0.01" name="amount_paid" id="amount_paid" x-model.number="settleAmount" required
                                                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Record Payment
                            </button>
                            <button type="button" @click="showSettleModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-500 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>