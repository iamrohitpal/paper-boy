<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('customers.index') }}"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add Customer') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mx-auto">
        <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Customer Name
                        *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Mobile -->
                <div>
                    <label for="mobile" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Mobile Number
                        *</label>
                    <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('mobile') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email
                        Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Start
                        Date *</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Area -->
                <div>
                    <label for="area" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Area</label>
                    <input type="text" name="area" id="area" value="{{ old('area') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                    @error('area') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block font-medium text-sm text-gray-700 dark:text-gray-300">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                    @error('city') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Pincode -->
                <div>
                    <label for="pincode"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">Pincode</label>
                    <input type="text" name="pincode" id="pincode" value="{{ old('pincode') }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                    @error('pincode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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

                <!-- Payment Frequency -->
                <div>
                    <label for="payment_frequency"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">Payment Frequency *</label>
                    <select name="payment_frequency" id="payment_frequency"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                        <option value="Daily" {{ old('payment_frequency') == 'Daily' ? 'selected' : '' }}>Daily</option>
                        <option value="Weekly" {{ old('payment_frequency') == 'Weekly' ? 'selected' : '' }}>Weekly
                        </option>
                        <option value="Monthly" {{ old('payment_frequency', 'Monthly') == 'Monthly' ? 'selected' : '' }}>
                            Monthly</option>
                    </select>
                    @error('payment_frequency') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Customer Photo -->
                <div>
                    <label for="customer_photo"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">Photo</label>
                    <input type="file" name="customer_photo" id="customer_photo"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                    @error('customer_photo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Address -->
                <div>
                    <label for="address" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Billing
                        Address</label>
                    <textarea name="address" id="address" rows="3"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">{{ old('address') }}</textarea>
                    @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Delivery Address -->
                <div>
                    <label for="delivery_address"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">Delivery Address (if
                        different)</label>
                    <textarea name="delivery_address" id="delivery_address" rows="3"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">{{ old('delivery_address') }}</textarea>
                    @error('delivery_address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('customers.index') }}"
                    class="mr-3 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Save
                    Customer</button>
            </div>
        </form>
    </div>
</x-app-layout>