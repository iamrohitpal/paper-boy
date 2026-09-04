<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('payments.index') }}"
                class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
                {{ __('messages.edit_payment') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 rounded-2xl border border-white/40 dark:border-gray-700 overflow-hidden">
            <div class="bg-gray-50/50 dark:bg-gray-900/50 px-8 py-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $payment->customer->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    @if($payment->invoice)
                        Invoice: <span class="font-semibold">{{ $payment->invoice->invoice_number }}</span>
                    @else
                        {{ __('messages.advance_unassigned') }}
                    @endif
                </p>
                <div class="mt-4 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-400 px-4 py-3 rounded-xl shadow-sm text-sm flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span><strong>Note:</strong> Changing the amount will update the customer's balance and invoice status.</span>
                </div>
            </div>

            <form action="{{ route('payments.update', $payment->id) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.amount') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 font-bold">₹</span>
                            </div>
                            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $payment->amount) }}"
                                class="pl-8 block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-semibold" required>
                        </div>
                        @error('amount') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <label for="payment_date" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.payment_date') }} <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all" required>
                        @error('payment_date') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Payment Mode -->
                    <div>
                        <label for="payment_mode" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.payment_mode') }} <span class="text-red-500">*</span></label>
                        <select name="payment_mode" id="payment_mode"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all" required>
                            <option value="Cash" {{ old('payment_mode', $payment->payment_mode) == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="UPI" {{ old('payment_mode', $payment->payment_mode) == 'UPI' ? 'selected' : '' }}>UPI (Google Pay, PhonePe, Paytm)</option>
                            <option value="Bank Transfer" {{ old('payment_mode', $payment->payment_mode) == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer (NEFT/IMPS)</option>
                            <option value="Cheque" {{ old('payment_mode', $payment->payment_mode) == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                        @error('payment_mode') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Transaction ID -->
                    <div>
                        <label for="transaction_id" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.transaction_id') }}</label>
                        <input type="text" name="transaction_id" id="transaction_id" value="{{ old('transaction_id', $payment->transaction_id) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-mono" placeholder="e.g. UTR Number">
                        @error('transaction_id') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="notes" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.notes') }}</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">{{ old('notes', $payment->notes) }}</textarea>
                    @error('notes') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end items-center gap-3">
                    <a href="{{ route('payments.index') }}"
                        class="px-5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">{{ __('messages.cancel') }}</a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-primary to-blue-600 hover:from-primary-dark hover:to-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all">{{ __('messages.update') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>