<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('payments.index') }}"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Record Payment') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mx-auto">
        <form action="{{ route('payments.store') }}" method="POST" class="p-6">
            @csrf

            @if($selectedInvoice)
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-md">
                    <h3 class="text-blue-800 dark:text-blue-200 font-semibold mb-2">Recording payment for Invoice
                        #{{ $selectedInvoice->invoice_number }}</h3>
                    <p class="text-sm text-blue-700 dark:text-blue-300">Customer: {{ $selectedInvoice->customer->name }}</p>
                    <p class="text-sm text-blue-700 dark:text-blue-300">Balance Due:
                        ₹{{ number_format($selectedInvoice->total_amount - $selectedInvoice->paid_amount, 2) }}</p>

                    <input type="hidden" name="customer_id" value="{{ $selectedInvoice->customer_id }}">
                    <input type="hidden" name="invoice_id" value="{{ $selectedInvoice->id }}">
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Customer -->
                    <div>
                        <label for="customer_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Customer
                            *</label>
                        <select name="customer_id" id="customer_id"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                            required>
                            <option value="">Select a Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->customer_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Invoice (Optional) -->
                    <div>
                        <label for="invoice_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Invoice
                            (Optional)</label>
                        <select name="invoice_id" id="invoice_id"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                            <option value="">Advance Payment / No Invoice</option>
                            @foreach($invoices as $invoice)
                                <option value="{{ $invoice->id }}" data-customer="{{ $invoice->customer_id }}"
                                    data-due="{{ $invoice->total_amount - $invoice->paid_amount }}" {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                    #{{ $invoice->invoice_number }} (Due:
                                    ₹{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('invoice_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Amount -->
                <div>
                    <label for="amount" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Amount (₹)
                        *</label>
                    <input type="number" step="0.01" name="amount" id="amount"
                        value="{{ old('amount', $selectedInvoice ? ($selectedInvoice->total_amount - $selectedInvoice->paid_amount) : '') }}"
                        min="1"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Payment Date -->
                <div>
                    <label for="payment_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Payment
                        Date *</label>
                    <input type="date" name="payment_date" id="payment_date"
                        value="{{ old('payment_date', date('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('payment_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Payment Mode -->
                <div>
                    <label for="payment_mode" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Payment
                        Mode *</label>
                    <select name="payment_mode" id="payment_mode"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                        <option value="Cash" {{ old('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="UPI" {{ old('payment_mode') == 'UPI' ? 'selected' : '' }}>UPI</option>
                        <option value="Bank Transfer" {{ old('payment_mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank
                            Transfer</option>
                        <option value="Cheque" {{ old('payment_mode') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                    </select>
                    @error('payment_mode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Transaction ID -->
                <div>
                    <label for="transaction_id"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">Transaction/Cheque ID</label>
                    <input type="text" name="transaction_id" id="transaction_id" value="{{ old('transaction_id') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                    <p class="mt-1 text-xs text-gray-500">Optional for Cash</p>
                    @error('transaction_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Notes
                    (Optional)</label>
                <textarea name="notes" id="notes" rows="3"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('payments.index') }}"
                    class="mr-3 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Record
                    Payment</button>
            </div>
        </form>
    </div>

    @if(!$selectedInvoice)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const customerSelect = document.getElementById('customer_id');
                const invoiceSelect = document.getElementById('invoice_id');
                const amountInput = document.getElementById('amount');

                // Filter invoices by selected customer
                function filterInvoices() {
                    const customerId = customerSelect.value;
                    const options = invoiceSelect.options;

                    for (let i = 1; i < options.length; i++) { // Skip first option
                        if (customerId === '' || options[i].getAttribute('data-customer') === customerId) {
                            options[i].style.display = '';
                        } else {
                            options[i].style.display = 'none';
                        }
                    }

                    // If currently selected invoice is hidden, reset to default
                    const selectedOption = options[invoiceSelect.selectedIndex];
                    if (selectedOption.style.display === 'none') {
                        invoiceSelect.selectedIndex = 0;
                    }
                }

                customerSelect.addEventListener('change', filterInvoices);

                // Auto-fill amount based on invoice
                invoiceSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const due = selectedOption.getAttribute('data-due');

                    if (due) {
                        amountInput.value = due;

                        // Auto-select customer if not selected
                        const customerId = selectedOption.getAttribute('data-customer');
                        if (customerSelect.value !== customerId) {
                            customerSelect.value = customerId;
                            filterInvoices();
                        }
                    }
                });

                // Initial filter
                if (customerSelect.value) {
                    filterInvoices();
                }
            });
        </script>
    @endif
</x-app-layout>