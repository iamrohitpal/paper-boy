<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('newspapers.index') }}"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add Newspaper') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mx-auto">
        <form action="{{ route('newspapers.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Newspaper Name
                        *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Publisher -->
                <div>
                    <label for="publisher"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">Publisher</label>
                    <input type="text" name="publisher" id="publisher" value="{{ old('publisher') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                    @error('publisher') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Language -->
                <div>
                    <label for="language" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Language
                        *</label>
                    <input type="text" name="language" id="language" value="{{ old('language', 'English') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('language') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- MRP -->
                <div>
                    <label for="mrp" class="block font-medium text-sm text-gray-700 dark:text-gray-300">MRP *</label>
                    <input type="number" step="0.01" name="mrp" id="mrp" value="{{ old('mrp') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('mrp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Purchase Price -->
                <div>
                    <label for="purchase_price"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">Purchase Price *</label>
                    <input type="number" step="0.01" name="purchase_price" id="purchase_price"
                        value="{{ old('purchase_price') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('purchase_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Selling Price (Weekday/Daily) -->
                <div>
                    <label for="selling_price"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">Selling Price (Daily)
                        *</label>
                    <input type="number" step="0.01" name="selling_price" id="selling_price"
                        value="{{ old('selling_price') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('selling_price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Selling Price (Sunday) -->
                <div>
                    <label for="selling_price_sunday"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">Selling Price
                        (Sunday)</label>
                    <input type="number" step="0.01" name="selling_price_sunday" id="selling_price_sunday"
                        value="{{ old('selling_price_sunday') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        placeholder="Leave empty if same as daily">
                    @error('selling_price_sunday') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Commission -->
                <div>
                    <label for="commission"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">Commission (Delivery
                        Boy)</label>
                    <input type="number" step="0.01" name="commission" id="commission"
                        value="{{ old('commission', 0) }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                    @error('commission') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Status
                        *</label>
                    <select name="status" id="status"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="description"
                    class="block font-medium text-sm text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('newspapers.index') }}"
                    class="mr-3 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Save
                    Newspaper</button>
            </div>
        </form>
    </div>
</x-app-layout>