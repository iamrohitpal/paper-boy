<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('extra-newspapers.index') }}"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('{{ __('messages.edit_extra_newspaper') }}') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mx-auto">
        <form action="{{ route('extra-newspapers.update', $extraNewspaper->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer -->
                <div>
                    <label for="customer_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.customer') }} *</label>
                    <select name="customer_id" id="customer_id"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                        <option value="">{{ __('messages.select_a_customer') }}</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $extraNewspaper->customer_id) == $customer->id ? 'selected' : '' }}>
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
                            <option value="{{ $newspaper->id }}" data-price="{{ $newspaper->selling_price }}" {{ old('newspaper_id', $extraNewspaper->newspaper_id) == $newspaper->id ? 'selected' : '' }}>
                                {{ $newspaper->name }} (₹{{ number_format($newspaper->selling_price, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('newspaper_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.date_delivered') }} *</label>
                    <input type="date" name="date" id="date"
                        value="{{ old('date', $extraNewspaper->date->format('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.quantity') }} *</label>
                    <input type="number" name="quantity" id="quantity"
                        value="{{ old('quantity', $extraNewspaper->quantity) }}" min="1"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.price_per_item') }} *</label>
                    <input type="number" step="0.01" name="price" id="price"
                        value="{{ old('price', $extraNewspaper->price) }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Billed Status -->
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="is_billed" id="is_billed" value="1"
                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        {{ old('is_billed', $extraNewspaper->is_billed) ? 'checked' : '' }}>
                    <label for="is_billed" class="ml-2 block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('messages.has_been_billed') }}</label>
                    @error('is_billed') <p class="ml-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('extra-newspapers.index') }}"
                    class="mr-3 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">{{ __('messages.cancel') }}</a>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">{{ __('messages.update_extra_newspaper') }}</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const newspaperSelect = document.getElementById('newspaper_id');
            const priceInput = document.getElementById('price');

            newspaperSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                if (price) {
                    priceInput.value = price;
                }
            });
        });
    </script>
</x-app-layout>