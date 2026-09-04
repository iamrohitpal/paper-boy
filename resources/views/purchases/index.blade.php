<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                {{ __('messages.vendor_purchases') }}
            </h2>
            <a href="{{ route('purchases.create') }}"
                class="px-5 py-2.5 bg-gradient-to-r from-primary to-blue-600 hover:from-primary-dark hover:to-blue-700 text-white rounded-xl text-sm font-semibold shadow-lg shadow-blue-500/30 transform hover:scale-105 hover:shadow-blue-500/50 transition-all duration-300 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('messages.record_purchase') }}
            </a>
        </div>
    </x-slot>

    <!-- Filter and Summaries -->
    <div class="max-w-7xl mx-auto space-y-6" x-data="{ showSettleModal: false, settlePublisher: '', settleAmount: 0, settleMonth: '{{ $month }}' }">

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl border border-white/40 dark:border-gray-700 p-5 transition-transform hover:-translate-y-1 duration-300">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider line-clamp-1">{{ __('messages.total_purchased') }}</h3>
                </div>
                <div class="text-xs text-gray-400 dark:text-gray-500 mb-1">({{ \Carbon\Carbon::parse($month)->format('M Y') }})</div>
                <p class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">₹{{ number_format($totalPurchased, 2) }}</p>
            </div>
            
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl border border-white/40 dark:border-gray-700 p-5 transition-transform hover:-translate-y-1 duration-300">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider line-clamp-1">{{ __('messages.total_paid') }}</h3>
                </div>
                <div class="h-4"></div>
                <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 tracking-tight">₹{{ number_format($totalPaid, 2) }}</p>
            </div>
            
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl border border-white/40 dark:border-gray-700 p-5 transition-transform hover:-translate-y-1 duration-300">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg text-purple-600 dark:text-purple-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider line-clamp-1">{{ __('messages.opening_balance_arrears') }}</h3>
                </div>
                <div class="h-4"></div>
                <p class="text-2xl font-black {{ $openingBalance > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-gray-900 dark:text-white' }} tracking-tight">₹{{ number_format($openingBalance, 2) }}</p>
            </div>
            
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl border border-white/40 dark:border-gray-700 p-5 transition-transform hover:-translate-y-1 duration-300">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-amber-100 dark:bg-amber-900/50 rounded-lg text-amber-600 dark:text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider line-clamp-1">{{ __('messages.months_net_balance') }}</h3>
                </div>
                <div class="h-4"></div>
                <p class="text-2xl font-black {{ $currentMonthBalance > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-gray-900 dark:text-white' }} tracking-tight">₹{{ number_format($currentMonthBalance, 2) }}</p>
            </div>
            
            <div class="bg-gradient-to-br from-rose-500 to-red-600 dark:from-rose-900/80 dark:to-red-900/80 shadow-lg rounded-2xl border border-rose-400/50 dark:border-red-700 p-5 transition-transform hover:-translate-y-1 duration-300 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-20">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xs font-bold text-white/90 uppercase tracking-wider line-clamp-1">{{ __('messages.total_outstanding_balance') }}</h3>
                    </div>
                    <div class="h-4"></div>
                    <p class="text-2xl font-black tracking-tight">₹{{ number_format($totalBalance, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Outstanding By Publisher (Only if there are balances) -->
        @if($publisherBalances->isNotEmpty())
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl border border-rose-200 dark:border-rose-900/50 overflow-hidden">
                <div class="px-5 py-4 border-b border-rose-100 dark:border-rose-900/30 bg-gradient-to-r from-rose-50 to-red-50 dark:from-rose-900/20 dark:to-red-900/20 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-rose-800 dark:text-rose-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ __('messages.outstanding_balances_to_distributors') }}
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($publisherBalances as $balance)
                        <div class="group relative flex flex-col p-4 bg-white dark:bg-gray-800 rounded-xl border border-rose-100 dark:border-rose-800/50 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-start justify-between mb-3">
                                <span class="text-sm font-bold text-gray-800 dark:text-gray-200 leading-tight pr-2">{{ $balance->publisher }}</span>
                                <span class="text-sm font-black text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/30 px-2 py-0.5 rounded text-nowrap">₹{{ number_format($balance->total_due, 2) }}</span>
                            </div>
                            <div class="mt-auto pt-2 text-right">
                                <button type="button" @click="showSettleModal = true; settlePublisher = '{{ addslashes($balance->publisher) }}'; settleAmount = {{ $balance->total_due }};" class="w-full inline-flex justify-center items-center gap-1 px-3 py-1.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transform group-hover:-translate-y-0.5 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    {{ __('messages.settle') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Filter Form -->
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl border border-white/40 dark:border-gray-700 p-5">
            <form action="{{ route('purchases.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('messages.month') }}</label>
                    <input type="month" name="month" value="{{ $month }}"
                        class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm sm:text-sm transition-all duration-300">
                </div>
                <div class="w-full sm:w-auto flex-1 max-w-sm">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">{{ __('messages.newspaper') }}</label>
                    <select name="newspaper_id"
                        class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm sm:text-sm transition-all duration-300">
                        <option value="">{{ __('messages.all_newspapers') }}</option>
                        @foreach($newspapers as $np)
                            <option value="{{ $np->id }}" {{ $newspaper_id == $np->id ? 'selected' : '' }}>{{ $np->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button type="submit"
                        class="px-5 py-2.5 bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 rounded-xl text-sm font-bold shadow-md hover:bg-gray-800 dark:hover:bg-white transition-colors w-full sm:w-auto flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        {{ __('messages.filter') }}
                    </button>
                    @if(request()->has('newspaper_id') && request('newspaper_id') != '')
                    <a href="{{ route('purchases.index') }}"
                        class="px-4 py-2.5 text-sm font-bold text-rose-600 dark:text-rose-400 bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/20 dark:hover:bg-rose-900/40 rounded-xl transition-colors">
                        {{ __('messages.clear') }}
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Purchases Table -->
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-lg rounded-2xl border border-white/40 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-white/50 dark:bg-gray-900/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.date') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.newspaper') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.qty_rate') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.total') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.paid') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.balance') }}</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-transparent">
                        @forelse($purchases as $purchase)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                    {{ $purchase->purchase_date->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $purchase->newspaper->name }}</div>
                                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-0.5">{{ $purchase->newspaper->publisher }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    <div class="font-medium bg-gray-100 dark:bg-gray-800 inline-block px-2 py-0.5 rounded">{{ $purchase->quantity }} @ ₹{{ number_format($purchase->rate, 2) }}</div>
                                    @if($purchase->return_quantity > 0)
                                        <div class="text-xs font-bold text-rose-500 dark:text-rose-400 mt-1.5 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                            Ret: {{ $purchase->return_quantity }} @ ₹{{ number_format($purchase->return_rate, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-black text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-800/50 px-2.5 py-1 rounded-md inline-block border border-gray-100 dark:border-gray-700">
                                        ₹{{ number_format($purchase->total_amount, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                        ₹{{ number_format($purchase->amount_paid, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($purchase->balance_due > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100/80 text-rose-700 border border-rose-200 dark:bg-rose-900/40 dark:text-rose-400 dark:border-rose-800/50 shadow-sm">
                                            ₹{{ number_format($purchase->balance_due, 2) }}
                                        </span>
                                    @elseif($purchase->balance_due < 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100/80 text-blue-700 border border-blue-200 dark:bg-blue-900/40 dark:text-blue-400 dark:border-blue-800/50 shadow-sm">
                                            ₹{{ number_format(abs($purchase->balance_due), 2) }} (Cr)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100/80 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-400 dark:border-emerald-800/50 shadow-sm">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            {{ __('messages.settled') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" class="inline-block"
                                        x-data @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this record?', onConfirm: () => $el.submit() })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:text-rose-900 dark:text-rose-400 dark:hover:text-rose-300 bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/30 dark:hover:bg-rose-900/50 rounded-lg transition-colors" title="{{ __('messages.delete') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-16 w-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 text-base font-medium">{{ __('messages.no_purchases_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($purchases->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    {{ $purchases->appends(['month' => $month, 'newspaper_id' => $newspaper_id])->links() }}
                </div>
            @endif
        </div>

        <!-- Settlement Modal -->
        <div x-show="showSettleModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showSettleModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="showSettleModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showSettleModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-700 relative z-10">
                    <form action="{{ route('purchases.settle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="month" x-model="settleMonth">
                        <input type="hidden" name="publisher" x-model="settlePublisher">
                        
                        <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-6">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/50 sm:mx-0 sm:h-12 sm:w-12 border border-blue-200 dark:border-blue-800">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modal-title">
                                        {{ __('messages.settle_account') }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium"><span x-text="settlePublisher"></span></p>
                                    
                                    <div class="mt-6 bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.amount_to_settle') }}</label>
                                        <p class="mt-1 text-3xl font-black text-rose-600 dark:text-rose-400 tracking-tight">₹<span x-text="Number(settleAmount).toFixed(2)"></span></p>
                                    </div>
                                    
                                    <div class="mt-6">
                                        <label for="amount_paid" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.amount_to_pay_today') }}</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <span class="text-gray-500 font-bold">₹</span>
                                            </div>
                                            <input type="number" step="0.01" name="amount_paid" id="amount_paid" x-model.number="settleAmount" required
                                                class="pl-8 block w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl shadow-sm transition-all font-bold text-lg">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-t border-gray-100 dark:border-gray-700 sm:flex sm:flex-row-reverse gap-3">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl border border-transparent shadow-lg shadow-blue-500/30 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-sm font-bold text-white hover:from-blue-700 hover:to-indigo-700 transform hover:-translate-y-0.5 transition-all">
                                {{ __('messages.record_payment') }}
                            </button>
                            <button type="button" @click="showSettleModal = false" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center items-center rounded-xl border border-gray-300 dark:border-gray-600 px-6 py-2.5 bg-white dark:bg-gray-800 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-colors">
                                {{ __('messages.cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>