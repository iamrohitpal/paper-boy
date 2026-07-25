<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Record Vendor Purchase') }}
        </h2>
    </x-slot>

    <div class="mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-100 dark:border-gray-700">
            <!-- Alpine Component -->
            <div x-data="purchaseForm()" class="p-6 sm:px-8">
                <form action="{{ route('purchases.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Newspaper Selection -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="newspaper_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Newspaper</label>
                            <select id="newspaper_id" name="newspaper_id" x-model="newspaperId" @change="updateRate()"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select Newspaper...</option>
                                @foreach($newspapers as $np)
                                    <option value="{{ $np->id }}" data-rate="{{ $np->purchase_price }}">{{ $np->name }}
                                        ({{ $np->publisher }})</option>
                                @endforeach
                            </select>
                            @error('newspaper_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Date -->
                        <div class="col-span-1">
                            <label for="purchase_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Purchase Date</label>
                            <input type="date" name="purchase_date" id="purchase_date"
                                value="{{ old('purchase_date', date('Y-m-d')) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('purchase_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="col-span-1">
                            <label for="payment_method"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment
                                Method</label>
                            <select name="payment_method" id="payment_method"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="UPI">UPI</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                            @error('payment_method') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Quantity -->
                        <div class="col-span-1">
                            <label for="quantity"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity
                                (Copies)</label>
                            <input type="number" name="quantity" id="quantity" x-model.number="quantity" min="1"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Rate -->
                        <div class="col-span-1">
                            <label for="rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rate
                                (Purchase Price per copy)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₹</span>
                                </div>
                                <input type="number" name="rate" id="rate" x-model.number="rate" step="0.01" min="0"
                                    required
                                    class="pl-7 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            @error('rate') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Return Quantity -->
                        <div class="col-span-1">
                            <label for="return_quantity"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Return Quantity
                                (Unsold Copies)</label>
                            <input type="number" name="return_quantity" id="return_quantity"
                                x-model.number="returnQuantity" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Number of unsold copies you are returning today.</p>
                        </div>

                        <!-- Return Rate -->
                        <div class="col-span-1">
                            <label for="return_rate"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Return Rate
                                (Credit per copy)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₹</span>
                                </div>
                                <input type="number" name="return_rate" id="return_rate" x-model.number="returnRate"
                                    step="0.01" min="0"
                                    class="pl-7 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Defaults to purchase rate. Change if returning Sunday
                                paper.</p>
                        </div>

                        <!-- Total (Calculated) -->
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total
                                Amount</label>
                            <div class="mt-1 relative rounded-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₹</span>
                                </div>
                                <input type="text" :value="totalAmount.toFixed(2)" readonly
                                    class="pl-7 block w-full rounded-md border-transparent bg-gray-50 dark:bg-gray-900 dark:text-gray-300 font-semibold text-gray-900 shadow-sm sm:text-sm focus:ring-0">
                            </div>
                        </div>

                        <!-- Amount Paid -->
                        <div class="col-span-1">
                            <label for="amount_paid"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount Paid
                                Now</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₹</span>
                                </div>
                                <input type="number" name="amount_paid" id="amount_paid" x-model.number="amountPaid"
                                    step="0.01" min="0" required
                                    class="pl-7 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                            </div>
                            @error('amount_paid') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Balance Due (Calculated) -->
                        <div class="col-span-1 md:col-span-2">
                            <div class="p-4 rounded-md"
                                :class="balanceDue > 0 ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' : 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800'">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium"
                                        :class="balanceDue > 0 ? 'text-red-800 dark:text-red-400' : 'text-green-800 dark:text-green-400'">Remaining
                                        Balance Due:</span>
                                    <span class="text-xl font-bold"
                                        :class="balanceDue > 0 ? 'text-red-800 dark:text-red-400' : 'text-green-800 dark:text-green-400'"
                                        x-text="'₹' + balanceDue.toFixed(2)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes
                                (Optional)</label>
                            <textarea id="notes" name="notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <a href="{{ route('purchases.index') }}"
                            class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">Cancel</a>
                        <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                            Record Purchase
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