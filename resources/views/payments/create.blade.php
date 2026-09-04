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
                {{ __('messages.record_payment') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto" x-data="{ 
        customers: [], 
        invoices: [], 
        selectedCustomer: '{{ old('customer_id', $preselectedCustomerId) }}', 
        selectedInvoice: '{{ old('invoice_id', $preselectedInvoiceId) }}',
        isLoadingInvoices: false,
        amount: '{{ old('amount') }}',
        
        async fetchInvoices() {
            if (!this.selectedCustomer) {
                this.invoices = [];
                return;
            }
            this.isLoadingInvoices = true;
            try {
                const response = await fetch(`/api/customers/${this.selectedCustomer}/unpaid-invoices`);
                this.invoices = await response.json();
                
                // If preselected invoice exists in the list, keep it selected, else clear it
                if(this.selectedInvoice && !this.invoices.some(i => i.id == this.selectedInvoice)) {
                    this.selectedInvoice = '';
                }
                
                this.updateAmount();
            } catch (error) {
                console.error('Error fetching invoices:', error);
            } finally {
                this.isLoadingInvoices = false;
            }
        },
        
        updateAmount() {
            if(this.selectedInvoice) {
                const invoice = this.invoices.find(i => i.id == this.selectedInvoice);
                if(invoice) {
                    this.amount = invoice.due_amount;
                }
            }
        },
        
        init() {
            if(this.selectedCustomer) {
                this.fetchInvoices();
            }
        }
    }">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 rounded-2xl border border-white/40 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('payments.store') }}" method="POST" class="p-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer -->
                    <div>
                        <label for="customer_id" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.customer') }} <span class="text-red-500">*</span></label>
                        <select name="customer_id" id="customer_id" x-model="selectedCustomer" @change="fetchInvoices()"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all" required>
                            <option value="">{{ __('messages.select_customer') }}</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->customer_id }})</option>
                            @endforeach
                        </select>
                        @error('customer_id') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Invoice -->
                    <div>
                        <label for="invoice_id" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">
                            {{ __('messages.invoice') }}
                            <span x-show="isLoadingInvoices" class="ml-2 text-xs text-indigo-500">Loading...</span>
                        </label>
                        <select name="invoice_id" id="invoice_id" x-model="selectedInvoice" @change="updateAmount()"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">
                            <option value="">{{ __('messages.advance_unassigned') }}</option>
                            <template x-for="invoice in invoices" :key="invoice.id">
                                <option :value="invoice.id" x-text="`${invoice.invoice_number} (Due: ₹${invoice.due_amount})`"></option>
                            </template>
                        </select>
                        <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Leave blank to record as an advance payment.</p>
                        @error('invoice_id') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.amount') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 font-bold">₹</span>
                            </div>
                            <input type="number" step="0.01" name="amount" id="amount" x-model="amount"
                                class="pl-8 block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-semibold" required>
                        </div>
                        @error('amount') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <label for="payment_date" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.payment_date') }} <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all" required>
                        @error('payment_date') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Payment Mode -->
                    <div>
                        <label for="payment_mode" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.payment_mode') }} <span class="text-red-500">*</span></label>
                        <select name="payment_mode" id="payment_mode"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all" required>
                            <option value="Cash" {{ old('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="UPI" {{ old('payment_mode') == 'UPI' ? 'selected' : '' }}>UPI (Google Pay, PhonePe, Paytm)</option>
                            <option value="Bank Transfer" {{ old('payment_mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer (NEFT/IMPS)</option>
                            <option value="Cheque" {{ old('payment_mode') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                        @error('payment_mode') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Transaction ID -->
                    <div>
                        <label for="transaction_id" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.transaction_id') }}</label>
                        <input type="text" name="transaction_id" id="transaction_id" value="{{ old('transaction_id') }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-mono" placeholder="e.g. UTR Number">
                        @error('transaction_id') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="notes" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.notes') }}</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">{{ old('notes') }}</textarea>
                    @error('notes') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end items-center gap-3">
                    <a href="{{ route('payments.index') }}"
                        class="px-5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">{{ __('messages.cancel') }}</a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-primary to-blue-600 hover:from-primary-dark hover:to-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>