<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('purchases.index') }}"
                class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
                {{ __('messages.record_vendor_purchase') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 rounded-2xl border border-white/40 dark:border-gray-700 overflow-hidden">
            <!-- Alpine Component -->
            <div x-data="purchaseForm()">
                <form action="{{ route('purchases.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Newspaper Selection -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="newspaper_id" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.newspaper') }} <span class="text-red-500">*</span></label>
                            <select id="newspaper_id" name="newspaper_id" x-model="newspaperId" @change="updateRate()" required
                                class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">
                                <option value="">{{ __('messages.select_newspaper_dots') }}</option>
                                @foreach($newspapers as $np)
                                    <option value="{{ $np->id }}" data-rate="{{ $np->purchase_price }}">{{ $np->name }} ({{ $np->publisher }})</option>
                                @endforeach
                            </select>
                            @error('newspaper_id') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Date -->
                        <div class="col-span-1">
                            <label for="purchase_date" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.purchase_date') }} <span class="text-red-500">*</span></label>
                            <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required
                                class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">
                            @error('purchase_date') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="col-span-1">
                            <label for="payment_method" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.payment_method') }} <span class="text-red-500">*</span></label>
                            <select name="payment_method" id="payment_method"
                                class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">
                                <option value="Cash">{{ __('messages.cash') }}</option>
                                <option value="Bank Transfer">{{ __('messages.bank_transfer') }}</option>
                                <option value="UPI">{{ __('messages.upi') }}</option>
                                <option value="Cheque">{{ __('messages.cheque') }}</option>
                            </select>
                            @error('payment_method') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-1 md:col-span-2"><hr class="border-gray-100 dark:border-gray-700"></div>

                        <!-- Quantity -->
                        <div class="col-span-1">
                            <label for="quantity" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.quantity_copies') }} <span class="text-red-500">*</span></label>
                            <input type="number" name="quantity" id="quantity" x-model.number="quantity" min="1" required
                                class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-semibold">
                            @error('quantity') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Rate -->
                        <div class="col-span-1">
                            <label for="rate" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.rate_purchase_price') }} <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 font-bold">₹</span>
                                </div>
                                <input type="number" name="rate" id="rate" x-model.number="rate" step="0.01" min="0" required
                                    class="pl-8 block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-semibold">
                            </div>
                            @error('rate') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Return Quantity -->
                        <div class="col-span-1">
                            <label for="return_quantity" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.return_quantity_unsold') }}</label>
                            <input type="number" name="return_quantity" id="return_quantity" x-model.number="returnQuantity" min="0"
                                class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-semibold text-rose-600 dark:text-rose-400">
                            <p class="mt-1.5 text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('messages.unsold_copies_returning_today') }}</p>
                        </div>

                        <!-- Return Rate -->
                        <div class="col-span-1">
                            <label for="return_rate" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.return_rate_credit') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 font-bold">₹</span>
                                </div>
                                <input type="number" name="return_rate" id="return_rate" x-model.number="returnRate" step="0.01" min="0"
                                    class="pl-8 block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-semibold">
                            </div>
                            <p class="mt-1.5 text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('messages.defaults_to_purchase_rate') }}</p>
                        </div>

                        <div class="col-span-1 md:col-span-2 bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 mt-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                <!-- Total (Calculated) -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('messages.total_amount') }} (Net)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-500 font-bold">₹</span>
                                        </div>
                                        <input type="text" :value="totalAmount.toFixed(2)" readonly
                                            class="pl-8 block w-full bg-transparent border-transparent text-2xl font-black text-gray-900 dark:text-white focus:ring-0 p-0">
                                    </div>
                                </div>

                                <!-- Amount Paid -->
                                <div>
                                    <label for="amount_paid" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('messages.amount_paid_now') }} <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-emerald-500 font-bold">₹</span>
                                        </div>
                                        <input type="number" name="amount_paid" id="amount_paid" x-model.number="amountPaid" step="0.01" min="0" required
                                            class="pl-8 block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-600 text-emerald-600 dark:text-emerald-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-xl shadow-sm transition-all font-black text-xl">
                                    </div>
                                    @error('amount_paid') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- Balance Due (Calculated) -->
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-center px-4 py-3 rounded-xl transition-colors"
                                    :class="balanceDue > 0 ? 'bg-rose-100/50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800/50' : 'bg-emerald-100/50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50'">
                                    <span class="text-sm font-bold uppercase tracking-wider"
                                        :class="balanceDue > 0 ? 'text-rose-800 dark:text-rose-400' : 'text-emerald-800 dark:text-emerald-400'">{{ __('messages.remaining_balance_due') }}</span>
                                    <span class="text-2xl font-black"
                                        :class="balanceDue > 0 ? 'text-rose-700 dark:text-rose-400' : 'text-emerald-700 dark:text-emerald-400'"
                                        x-text="'₹' + balanceDue.toFixed(2)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="notes" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.notes_optional') }}</label>
                            <textarea id="notes" name="notes" rows="2"
                                class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all"></textarea>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end items-center gap-3">
                        <a href="{{ route('purchases.index') }}"
                            class="px-5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">{{ __('messages.cancel') }}</a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-primary to-blue-600 hover:from-primary-dark hover:to-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('messages.record_purchase') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alpine Script -->
    <script>
        function purchaseForm() {
            return {
                newspaperId: '',
                quantity: 0,
                rate: 0,
                returnQuantity: 0,
                returnRate: 0,
                amountPaid: 0,

                updateRate() {
                    const select = document.getElementById('newspaper_id');
                    if (select.selectedIndex > 0) {
                        const option = select.options[select.selectedIndex];
                        const newRate = parseFloat(option.getAttribute('data-rate')) || 0;
                        this.rate = newRate;
                        this.returnRate = newRate;
                    } else {
                        this.rate = 0;
                        this.returnRate = 0;
                    }
                },

                get totalAmount() {
                    let gross = (this.quantity || 0) * (this.rate || 0);
                    let credit = (this.returnQuantity || 0) * (this.returnRate || 0);
                    return gross - credit;
                },

                get balanceDue() {
                    let balance = this.totalAmount - (this.amountPaid || 0);
                    return balance;
                }
            }
        }
    </script>
</x-app-layout>