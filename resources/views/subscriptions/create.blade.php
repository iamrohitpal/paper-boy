<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('subscriptions.index') }}"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('{{ __('messages.add_subscription') }}') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mx-auto"
        x-data="{ deliveryType: '{{ old('delivery_days', 'Daily') }}' }">
        <form action="{{ route('subscriptions.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer -->
                <div>
                    <label for="customer_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.customer') }} *</label>
                    <select name="customer_id" id="customer_id"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                        <option value="">{{ __('messages.select_a_customer') }}</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->customer_id }})
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Newspaper -->
                <div>
                    <label for="newspaper_id"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.newspaper') }} *</label>
                    <select name="newspaper_id" id="newspaper_id"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                        <option value="">{{ __('messages.select_a_newspaper') }}</option>
                        @foreach($newspapers as $newspaper)
                            <option value="{{ $newspaper->id }}" data-price="{{ $newspaper->selling_price }}" {{ old('newspaper_id') == $newspaper->id ? 'selected' : '' }}>
                                {{ $newspaper->name }} (₹{{ number_format($newspaper->selling_price, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('newspaper_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.quantity') }} *</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Price Override -->
                <div>
                    <label for="price" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.agreed_price_per_item') }} *</label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    <p class="text-xs text-gray-500 mt-1">{{ __('messages.different_from_mrp') }}</p>
                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.start_date') }} *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.end_date_optional') }}</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                    @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Delivery Days -->
                <div>
                    <label for="delivery_days"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.delivery_schedule') }} *</label>
                    <select name="delivery_days" id="delivery_days" x-model="deliveryType"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                        <option value="Daily">{{ __('messages.daily') }}</option>
                        <option value="Sunday Only">{{ __('messages.sunday_only') }}</option>
                        <option value="Custom">{{ __('messages.custom_days') }}</option>
                    </select>
                    @error('delivery_days') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.status') }} *</label>
                    <select name="status" id="status"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                        <option value="Paused" {{ old('status') == 'Paused' ? 'selected' : '' }}>{{ __('messages.paused') }}</option>
                        <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                    </select>
                    @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Custom Days Checkboxes -->
            <div x-show="deliveryType === 'Custom'"
                class="mt-6 p-4 border border-gray-200 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-750"
                style="display: none;">
                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.select_custom_delivery_days') }} *</label>
                <div class="flex flex-wrap gap-4">
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="custom_days[]" value="{{ $day }}"
                                class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                {{ is_array(old('custom_days')) && in_array($day, old('custom_days')) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $day }}</span>
                        </label>
                    @endforeach
                </div>
                @error('custom_days') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('subscriptions.index') }}"
                    class="mr-3 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('messages.cancel') }}</a>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">{{ __('messages.save_subscription') }}</button>
            </div>
        </form>
    </div>

    <script>
        // Auto-fill price when newspaper is selected
        document.addEventListener('DOMContentLoaded', function () {
            const newspaperSelect = document.getElementById('newspaper_id');
            const priceInput = document.getElementById('price');

            newspaperSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                if (price && !priceInput.value) {
                    priceInput.value = price;
                }
            });
        });
    </script>
</x-app-layout>