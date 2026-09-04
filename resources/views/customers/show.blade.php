<x-app-layout>
    <div
        x-data="{ showPaymentModal: false, showSubscriptionModal: false, showLeaveModal: false, showExtraModal: false, showGenerateBillModal: false, showEditSubscriptionModal: false, showEditExtraModal: false, showEditLeaveModal: false, editSub: { id: '', customer_id: '', newspaper_id: '', billing_type: '', start_date: '', quantity: 1, price: 0, delivery_days: '' }, editExtra: { id: '', date: '', newspaper_id: '', quantity: 1, price: 0 }, editLeave: { old_start_date: '', old_end_date: '', old_newspaper_id: '', start_date: '', end_date: '', newspaper_id: '' }, paymentInvoiceId: '', paymentAmount: '{{ $totalBalance > 0 ? $totalBalance : '' }}', openPaymentModal(invoiceId = '', dueAmount = '') { this.paymentInvoiceId = invoiceId; this.paymentAmount = dueAmount !== '' ? dueAmount : '{{ $totalBalance > 0 ? $totalBalance : '' }}'; this.showPaymentModal = true; } }">
        <!-- Page Header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Customer Ledger
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{ __('messages.billing_and_payment_history_for') }}
                    {{ $customer->name }}
                </p>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center space-x-4">
                <button @click="openPaymentModal()"
                    class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 shadow-lg shadow-green-500/30 transform hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-900 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('messages.receive_payment') }}
                </button>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                        class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md hover:bg-gray-50 dark:hover:bg-gray-700/80 shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-gray-900 transition-colors">
                        {{ __('messages.delivery_actions') }}
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition
                        class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none z-10"
                        style="display: none;">
                        <div class="py-1">
                            <button @click="showLeaveModal = true; open = false"
                                class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('messages.log_leave') }}
                            </button>
                            <button @click="showExtraModal = true; open = false"
                                class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('messages.log_extra_paper') }}
                            </button>
                        </div>
                        <div class="py-1">
                            <button @click="showGenerateBillModal = true; open = false"
                                class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ __('messages.generate_bill') }}
                            </button>
                        </div>
                    </div>
                </div>
                <a href="{{ route('customers.edit', $customer->id) }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white/70 dark:bg-gray-800/70 backdrop-blur-md hover:bg-gray-50 dark:hover:bg-gray-700/80 shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-gray-900 transition-colors">
                    {{ __('messages.edit_profile') }}
                </a>
            </div>
        </div>

        <!-- Customer Summary Card -->
        <div
            class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 border border-white/40 dark:border-gray-700 mb-8 overflow-hidden">
            <div class="p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $customer->name }}</h2>
                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400 space-y-1">
                            <p><span class="font-medium">{{ __('messages.mobile') }}:</span> {{ $customer->mobile }}</p>
                            <p><span class="font-medium">{{ __('messages.address') }}:</span> {{ $customer->address }}</p>
                            <p><span class="font-medium">{{ __('messages.status') }}:</span>
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $customer->status === 'Active' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ $customer->status }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ __('messages.total_balance_due') }}</p>
                        <p
                            class="text-3xl font-bold {{ $totalBalance > 0 ? 'text-red-600 dark:text-red-400' : ($totalBalance < 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white') }}">
                            ₹{{ number_format($totalBalance, 2) }}
                        </p>
                        @if($totalBalance < 0)
                            <p class="text-xs text-green-600 dark:text-green-400 mt-1">{{ __('messages.advance_payment') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions -->
        <div
            class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 border border-white/40 dark:border-gray-700 mb-8 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.active_newspapers') }}</h2>
                <button @click="showSubscriptionModal = true"
                    class="inline-flex items-center text-sm p-1.5 text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:hover:text-blue-300 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 rounded-lg transition-colors dark:text-indigo-400 dark:hover:text-indigo-300">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('messages.add_newspaper') }}
                </button>
            </div>
            <div class="p-6">
                @if($customer->subscriptions->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($customer->subscriptions as $sub)
                            <div
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white">{{ $sub->newspaper->name }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.rate') }}:
                                        ₹{{ $sub->newspaper->selling_price }} |
                                        {{ __('messages.qty') }}: {{ $sub->quantity }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Start Date: {{ date('d-M-Y', strtotime($sub->start_date)) }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button type="button" @click="editSub.id = '{{ $sub->id }}'; editSub.customer_id = '{{ $sub->customer_id }}'; editSub.newspaper_id = '{{ $sub->newspaper_id }}'; editSub.billing_type = '{{ $sub->billing_type }}'; editSub.start_date = '{{ \Carbon\Carbon::parse($sub->start_date)->format('Y-m-d') }}'; editSub.quantity = {{ $sub->quantity }}; editSub.price = {{ $sub->price }}; editSub.delivery_days = '{{ $sub->delivery_days }}'; showEditSubscriptionModal = true" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('subscriptions.destroy', $sub->id) }}" method="POST"
                                        class="inline-block"
                                        x-data @submit.prevent="$dispatch('open-confirm', { message: 'Remove this subscription?', onConfirm: () => $el.submit() })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 p-1.5 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 dark:text-red-400 dark:hover:text-red-300 dark:bg-red-900/30 dark:hover:bg-red-900/50 rounded-lg transition-colors bg-red-50 hover:bg-red-100 dark:text-red-400 dark:hover:text-red-300 dark:bg-red-900/30 dark:hover:bg-red-900/50 rounded-lg transition-colors dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-sm text-center">No active subscriptions for this
                        customer.</p>
                @endif
            </div>
        </div>

        <!-- {{ __('messages.extra_newspapers') }} -->
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 border border-white/40 dark:border-gray-700 mb-8 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.extra_newspapers') }}</h2>
                <button @click="showExtraModal = true"
                    class="inline-flex items-center text-sm p-1.5 text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:hover:text-blue-300 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 rounded-lg transition-colors dark:text-indigo-400 dark:hover:text-indigo-300">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('messages.log_extra_paper') }}
                </button>
            </div>
            
            @if($customer->extraNewspapers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.date') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.newspaper') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.quantity') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.price') }} (₹)</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($customer->extraNewspapers->sortByDesc('date') as $extra)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($extra->date)->format('d M, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $extra->newspaper->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                        {{ $extra->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                        ₹{{ number_format($extra->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        @if($extra->is_billed)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Billed</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                        @if(!$extra->is_billed)
                                            <button @click="editExtra.id = '{{ $extra->id }}'; editExtra.date = '{{ \Carbon\Carbon::parse($extra->date)->format('Y-m-d') }}'; editExtra.newspaper_id = '{{ $extra->newspaper_id }}'; editExtra.quantity = {{ $extra->quantity }}; editExtra.price = {{ $extra->price }}; showEditExtraModal = true" class="p-1.5 text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:hover:text-blue-300 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 rounded-lg transition-colors dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">{{ __('messages.edit') }}</button>
                                            <form action="{{ route('extra-newspapers.destroy', $extra->id) }}" method="POST" class="inline" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Delete this extra paper?', onConfirm: () => $el.submit() })">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 dark:text-red-400 dark:hover:text-red-300 dark:bg-red-900/30 dark:hover:bg-red-900/50 rounded-lg transition-colors dark:text-red-400 dark:hover:text-red-300">{{ __('messages.delete') }}</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6">
                    <p class="text-gray-500 dark:text-gray-400 text-sm text-center">{{ __('messages.no_extra_newspapers') }}</p>
                </div>
            @endif
        </div>

        <!-- Customer Leaves -->
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 border border-white/40 dark:border-gray-700 mb-8 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.customer_leaves') }}</h2>
                <button @click="showLeaveModal = true"
                    class="inline-flex items-center text-sm p-1.5 text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:hover:text-blue-300 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 rounded-lg transition-colors dark:text-indigo-400 dark:hover:text-indigo-300">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('messages.log_leave') }}
                </button>
            </div>
            
            @if($groupedLeaves->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.start_date') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.end_date') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.duration') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.newspaper') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($groupedLeaves as $leave)
                                @php
                                    $start = \Carbon\Carbon::parse($leave['start_date']);
                                    $end = \Carbon\Carbon::parse($leave['end_date']);
                                    $days = $start->diffInDays($end) + 1;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $start->format('d M, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $end->format('d M, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $days }} {{ Str::plural('day', $days) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $leave['newspaper_name'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                        <button @click="editLeave.old_start_date = '{{ $start->format('Y-m-d') }}'; editLeave.old_end_date = '{{ $end->format('Y-m-d') }}'; editLeave.old_newspaper_id = '{{ $leave['newspaper_id'] ?? '' }}'; editLeave.start_date = '{{ $start->format('Y-m-d') }}'; editLeave.end_date = '{{ $end->format('Y-m-d') }}'; editLeave.newspaper_id = '{{ $leave['newspaper_id'] ?? '' }}'; showEditLeaveModal = true" class="p-1.5 text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:hover:text-blue-300 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 rounded-lg transition-colors dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">{{ __('messages.edit') }}</button>
                                        <form action="{{ route('customers.leaves.destroy') }}" method="POST" class="inline" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Delete this leave period?', onConfirm: () => $el.submit() })">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                            <input type="hidden" name="start_date" value="{{ $start->format('Y-m-d') }}">
                                            <input type="hidden" name="end_date" value="{{ $end->format('Y-m-d') }}">
                                            <input type="hidden" name="newspaper_id" value="{{ $leave['newspaper_id'] ?? '' }}">
                                            <button type="submit" class="p-1.5 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 dark:text-red-400 dark:hover:text-red-300 dark:bg-red-900/30 dark:hover:bg-red-900/50 rounded-lg transition-colors dark:text-red-400 dark:hover:text-red-300">{{ __('messages.delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6">
                    <p class="text-gray-500 dark:text-gray-400 text-sm text-center">{{ __('messages.no_leaves_recorded') }}</p>
                </div>
            @endif
        </div>

        <!-- Ledger Table -->
        <div
            class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 border border-white/40 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.ledger_history') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Date</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Type</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Description</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.charge_bill') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.payment_receipt') }}</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Balance</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($ledger as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($row['date'])->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $row['type'] === 'Invoice' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' }}">
                                        {{ $row['type'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    <div class="flex items-center">
                                        {{ $row['description'] }}
                                        @if($row['type'] === 'Invoice' && isset($row['id']))
                                            <a href="{{ route('invoices.pdf', $row['id']) }}" target="_blank"
                                                class="ml-2 p-1.5 text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:hover:text-blue-300 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 rounded-lg transition-colors dark:text-indigo-400 dark:hover:text-indigo-300"
                                                title="Download PDF">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                    </path>
                                                </svg>
                                            </a>
                                            
                                            <form action="{{ route('invoices.whatsapp', $row['id']) }}" method="POST" class="inline" title="Send via WhatsApp" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Send WhatsApp bill notification for Invoice #{{ addslashes($row['description']) }} to {{ addslashes($customer->name) }}?', onConfirm: () => $el.submit() })">
                                                @csrf
                                                <button type="submit" class="ml-2 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                            
                                            <span class="ml-2 text-xs font-medium px-2 py-0.5 rounded-full {{ $row['status'] === 'Paid' ? 'bg-green-100 text-green-800' : ($row['status'] === 'Partially Paid' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $row['status'] }}
                                            </span>
                                            
                                            @if(in_array($row['status'], ['Unpaid', 'Partially Paid']))
                                                <span class="ml-2 text-xs text-red-600 dark:text-red-400 font-semibold">
                                                    ₹{{ number_format($row['due_amount'], 2) }} due
                                                </span>
                                                <button @click="openPaymentModal({{ $row['invoice_id'] }}, {{ $row['due_amount'] }})"
                                                    class="ml-3 text-xs bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50 px-2 py-1 rounded border border-indigo-200 dark:border-indigo-800 transition-colors">
                                                    Pay Bill
                                                </button>
                                                
                                                @if($row['status'] === 'Unpaid')
                                                <form action="{{ route('invoices.destroy', $row['invoice_id']) }}" method="POST" class="inline" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this bill? You can then regenerate it with a new date.', onConfirm: () => $el.submit() })">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="ml-2 text-xs bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 px-2 py-1 rounded border border-red-200 dark:border-red-800 transition-colors" title="Delete & Regenerate">
                                                        <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-white">
                                    {{ $row['charge'] > 0 ? '₹' . number_format($row['charge'], 2) : '-' }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">
                                    {{ $row['payment'] > 0 ? '₹' . number_format($row['payment'], 2) : '-' }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $row['balance'] > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                    ₹{{ number_format($row['balance'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <p>{{ __('messages.no_billing_history') }}</p>
                                </td>
                            </tr>
                        @endforelse
                        
                        @if(isset($unbilledAmount) && $unbilledAmount > 0)
                            <tr class="bg-yellow-50 dark:bg-yellow-900/10">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                    {{ \Carbon\Carbon::now()->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">
                                        Pending
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('messages.unbilled_charges') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-white">
                                    ₹{{ number_format($unbilledAmount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-500">
                                    -
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-red-600 dark:text-red-400">
                                    ₹{{ number_format($totalBalance, 2) }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Subscription Modal -->
        <div x-show="showSubscriptionModal" class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showSubscriptionModal" @click="showSubscriptionModal = false"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showSubscriptionModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form action="{{ route('subscriptions.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <input type="hidden" name="delivery_days" value="Daily">
                        <input type="hidden" name="status" value="Active">

                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full" x-data="{ 
                                    newspaperPrices: {
                                        @foreach($newspapers as $np)
                                            '{{ $np->id }}': {
                                                daily: {{ $np->selling_price ?: 0 }},
                                                sunday: {{ $np->selling_price_sunday ?: 'null' }}
                                            }{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    },
                                    selectedNewspaper: '',
                                    currentPrice: 0,
                                    currentSundayPrice: '',
                                    updatePrice() {
                                        if (this.selectedNewspaper && this.newspaperPrices[this.selectedNewspaper]) {
                                            const prices = this.newspaperPrices[this.selectedNewspaper];
                                            this.currentPrice = prices.daily;
                                            this.currentSundayPrice = prices.sunday !== null ? prices.sunday : '';
                                        } else {
                                            this.currentPrice = 0;
                                            this.currentSundayPrice = '';
                                        }
                                    }
                                }">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                        id="modal-title">
                                        {{ __('messages.add_newspaper') }} Subscription
                                    </h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="newspaper_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.newspaper') }}</label>
                                            <select name="newspaper_id" id="newspaper_id" x-model="selectedNewspaper"
                                                @change="updatePrice()"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                                required>
                                                <option value="">Select Newspaper</option>
                                                @foreach($newspapers as $newspaper)
                                                    <option value="{{ $newspaper->id }}">{{ $newspaper->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                            <input type="hidden" name="billing_type" value="Daily">

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="quantity"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.quantity') }}</label>
                                                <input type="number" name="quantity" id="quantity" value="1" min="1"
                                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    required>
                                            </div>
                                            <div>
                                                <label for="start_date"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start
                                                    Date</label>
                                                <input type="date" name="start_date" id="start_date"
                                                    value="{{ date('Y-m-d') }}"
                                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="price"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price
                                                    (₹)</label>
                                                <input type="number" name="price" id="price" step="0.01" min="0"
                                                    x-model="currentPrice"
                                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    required>
                                                <p class="text-xs text-gray-500 mt-1">Base price per day</p>
                                            </div>
                                            <div>
                                                <label for="price_sunday"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sunday
                                                    Price (₹)</label>
                                                <input type="number" name="price_sunday" id="price_sunday" step="0.01"
                                                    min="0" x-model="currentSundayPrice"
                                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                <p class="text-xs text-gray-500 mt-1">Leave empty if same</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Save Subscription
                            </button>
                            <button type="button" @click="showSubscriptionModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <div x-show="showPaymentModal" class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showPaymentModal" @click="showPaymentModal = false"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showPaymentModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <input type="hidden" name="invoice_id" x-model="paymentInvoiceId">

                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                        id="modal-title">
                                        {{ __('messages.receive_payment') }}
                                    </h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="amount"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount
                                                Collected</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">₹</span>
                                                </div>
                                                <input type="number" name="amount" id="amount" step="0.01"
                                                    x-model="paymentAmount"
                                                    class="focus:ring-primary focus:border-primary block w-full pl-7 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    required>
                                            </div>
                                        </div>

                                        <div>
                                            <label for="payment_date"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.date') }}</label>
                                            <input type="date" name="payment_date" id="payment_date"
                                                value="{{ date('Y-m-d') }}"
                                                class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                required>
                                        </div>

                                        <div>
                                            <label for="payment_mode"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment
                                                Mode</label>
                                            <select name="payment_mode" id="payment_mode"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                                required>
                                                <option value="Cash">Cash</option>
                                                <option value="UPI">UPI</option>
                                                <option value="Bank Transfer">Bank Transfer</option>
                                                <option value="Cheque">Cheque</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="notes"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes
                                                (Optional)</label>
                                            <input type="text" name="notes" id="notes"
                                                placeholder="e.g. Daily collection, Part payment"
                                                class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Save Payment
                            </button>
                            <button type="button" @click="showPaymentModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Leave Modal -->
        <div x-show="showLeaveModal" class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showLeaveModal" @click="showLeaveModal = false" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showLeaveModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form action="{{ route('customers.leaves.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                        id="modal-title">
                                        {{ __('messages.log_leave') }} (Suspension)
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">Select a date range where the customer will
                                        not receive newspapers. They will not be billed for these days.</p>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="leave_newspaper_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Newspaper
                                                (Optional)</label>
                                            <select name="newspaper_id" id="leave_newspaper_id"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md">
                                                <option value="">All Active Subscriptions</option>
                                                @foreach($customer->subscriptions as $sub)
                                                    <option value="{{ $sub->newspaper_id }}">{{ $sub->newspaper->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="start_date"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">From
                                                    Date</label>
                                                <input type="date" name="start_date" id="start_date"
                                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    required>
                                            </div>
                                            <div>
                                                <label for="end_date"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">To
                                                    Date</label>
                                                <input type="date" name="end_date" id="end_date"
                                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Save Leave
                            </button>
                            <button type="button" @click="showLeaveModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Extra Paper Modal -->
        <div x-show="showExtraModal" class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showExtraModal" @click="showExtraModal = false" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showExtraModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form action="{{ route('extra-newspapers.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full" x-data="{
                                    newspaperPrices: {
                                        @foreach($newspapers as $np)
                                            '{{ $np->id }}': {
                                                daily: {{ $np->selling_price ?: 0 }},
                                                sunday: {{ $np->selling_price_sunday ?: 'null' }}
                                            }{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    },
                                    selectedExtraNewspaper: '',
                                    extraDate: '{{ date('Y-m-d') }}',
                                    extraQuantity: 1,
                                    extraPrice: 0,
                                    updateExtraPrice() {
                                        if (this.selectedExtraNewspaper && this.newspaperPrices[this.selectedExtraNewspaper]) {
                                            const prices = this.newspaperPrices[this.selectedExtraNewspaper];
                                            const dateObj = new Date(this.extraDate);
                                            let unitPrice = prices.daily;
                                            
                                            if (dateObj.getDay() === 0 && prices.sunday !== null) {
                                                unitPrice = prices.sunday;
                                            }
                                            
                                            this.extraPrice = (unitPrice * this.extraQuantity).toFixed(2);
                                        } else {
                                            this.extraPrice = 0;
                                        }
                                    }
                                }">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                        id="modal-title">
                                        Log Extra Newspaper
                                    </h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="extra_newspaper_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.newspaper') }}</label>
                                            <select name="newspaper_id" id="extra_newspaper_id"
                                                x-model="selectedExtraNewspaper" @change="updateExtraPrice()"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                                required>
                                                <option value="">Select Newspaper</option>
                                                @foreach($newspapers as $newspaper)
                                                    <option value="{{ $newspaper->id }}">{{ $newspaper->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="extra_date"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.date') }}</label>
                                            <input type="date" name="date" id="extra_date" 
                                                x-model="extraDate" @change="updateExtraPrice()"
                                                class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                required>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="extra_quantity"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.quantity') }}</label>
                                                <input type="number" name="quantity" id="extra_quantity" 
                                                    x-model="extraQuantity" @input="updateExtraPrice()"
                                                    min="1"
                                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    required>
                                            </div>
                                            <div>
                                                <label for="extra_price"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total
                                                    Price (₹)</label>
                                                <input type="number" name="price" id="extra_price" step="0.01" min="0"
                                                    x-model="extraPrice"
                                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Save Extra Paper
                            </button>
                            <button type="button" @click="showExtraModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- {{ __('messages.generate_bill') }} Modal -->
        <div x-show="showGenerateBillModal" class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showGenerateBillModal" @click="showGenerateBillModal = false"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="showGenerateBillModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form action="{{ route('customers.generate-bill', $customer->id) }}" method="POST">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                        id="modal-title">
                                        {{ __('messages.generate_bill') }}
                                    </h3>
                                    <div class="mt-4 space-y-4">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            This will instantly generate a finalized invoice for all unbilled subscription days and extra papers up to the selected date.
                                        </p>
                                        <div>
                                            <label for="bill_date"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bill Up To Date</label>
                                            <input type="date" name="bill_date" id="bill_date" 
                                                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                {{ __('messages.generate_bill') }}
                            </button>
                            <button type="button" @click="showGenerateBillModal = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Subscription Modal -->
        <div x-show="showEditSubscriptionModal" class="fixed z-50 inset-0 overflow-y-auto" style="display: none;"
            x-data="{
                newspaperPrices: {
                    @foreach($newspapers as $np)
                        '{{ $np->id }}': {
                            daily: {{ $np->selling_price ?: 0 }},
                            sunday: {{ $np->selling_price_sunday ?: 'null' }}
                        }{{ !$loop->last ? ',' : '' }}
                    @endforeach
                },
                updateEditPrice() {
                    if (this.editSub.newspaper_id && this.newspaperPrices[this.editSub.newspaper_id]) {
                        const prices = this.newspaperPrices[this.editSub.newspaper_id];
                        this.editSub.price = prices.daily;
                    }
                }
            }">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditSubscriptionModal" @click="showEditSubscriptionModal = false"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showEditSubscriptionModal"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form :action="'{{ url('subscriptions') }}/' + editSub.id" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <input type="hidden" name="customer_id" x-model="editSub.customer_id">
                            <input type="hidden" name="billing_type" value="Daily">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Edit Subscription</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.newspaper') }}</label>
                                    <select name="newspaper_id" x-model="editSub.newspaper_id" @change="updateEditPrice()" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                                        @foreach($newspapers as $newspaper)
                                            <option value="{{ $newspaper->id }}">{{ $newspaper->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.start_date') }}</label>
                                        <input type="date" name="start_date" x-model="editSub.start_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.quantity') }}</label>
                                        <input type="number" name="quantity" min="1" x-model="editSub.quantity" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.price') }} (₹)</label>
                                        <input type="number" name="price" step="0.01" min="0" x-model="editSub.price" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Delivery Days</label>
                                        <select name="delivery_days" x-model="editSub.delivery_days" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            <option value="Daily">Daily</option>
                                            <option value="Sunday Only">Sunday Only</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('messages.save_changes') }}</button>
                            <button type="button" @click="showEditSubscriptionModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">{{ __('messages.cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Extra Paper Modal -->
        <div x-show="showEditExtraModal" class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditExtraModal" @click="showEditExtraModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showEditExtraModal" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form :action="'{{ url('extra-newspapers') }}/' + editExtra.id" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Edit Extra Newspaper</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.newspaper') }}</label>
                                    <select name="newspaper_id" x-model="editExtra.newspaper_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                                        @foreach($newspapers as $newspaper)
                                            <option value="{{ $newspaper->id }}">{{ $newspaper->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.date') }}</label>
                                    <input type="date" name="date" x-model="editExtra.date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.quantity') }}</label>
                                    <input type="number" name="quantity" min="1" x-model="editExtra.quantity" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Price (₹)</label>
                                    <input type="number" name="price" step="0.01" min="0" x-model="editExtra.price" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('messages.save_changes') }}</button>
                            <button type="button" @click="showEditExtraModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">{{ __('messages.cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Leave Modal -->
        <div x-show="showEditLeaveModal" class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditLeaveModal" @click="showEditLeaveModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showEditLeaveModal" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form action="{{ route('customers.leaves.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <input type="hidden" name="old_start_date" x-model="editLeave.old_start_date">
                        <input type="hidden" name="old_end_date" x-model="editLeave.old_end_date">
                        <input type="hidden" name="old_newspaper_id" x-model="editLeave.old_newspaper_id">
                        
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Edit Leave Period</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Newspaper (Optional)</label>
                                    <select name="newspaper_id" x-model="editLeave.newspaper_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <option value="">All Active Subscriptions</option>
                                        @foreach($customer->subscriptions as $sub)
                                            <option value="{{ $sub->newspaper_id }}">{{ $sub->newspaper->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">From Date</label>
                                        <input type="date" name="start_date" x-model="editLeave.start_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">To Date</label>
                                        <input type="date" name="end_date" x-model="editLeave.end_date" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">{{ __('messages.save_changes') }}</button>
                            <button type="button" @click="showEditLeaveModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">{{ __('messages.cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>