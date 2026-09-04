<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('customers.index') }}"
                class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
                {{ __('messages.edit_customer') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 rounded-2xl border border-white/40 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')

                <!-- Photo Preview Section -->
                @if($customer->customer_photo)
                <div class="mb-8 flex items-center gap-6 p-4 bg-gray-50/50 dark:bg-gray-900/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <img src="{{ asset('storage/' . $customer->customer_photo) }}" alt="{{ $customer->name }}" class="h-24 w-24 rounded-full object-cover shadow-md border-4 border-white dark:border-gray-800">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $customer->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->customer_id }}</p>
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer ID -->
                    <div>
                        <label for="customer_id"
                            class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.customer_id') }}
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_id" id="customer_id"
                            value="{{ old('customer_id', $customer->customer_id) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all"
                            required>
                        @error('customer_id') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.name') }}
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all"
                            required>
                        @error('name') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Mobile -->
                    <div>
                        <label for="mobile" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.mobile') }}
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="mobile" id="mobile" value="{{ old('mobile', $customer->mobile) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all"
                            required>
                        @error('mobile') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email"
                            class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">
                        @error('email') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Area -->
                    <div>
                        <label for="area" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.area') }}</label>
                        <input type="text" name="area" id="area" value="{{ old('area', $customer->area) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">
                        @error('area') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.status') }}
                            <span class="text-red-500">*</span></label>
                        <select name="status" id="status"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">
                            <option value="Active" {{ old('status', $customer->status) == 'Active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                            <option value="Inactive" {{ old('status', $customer->status) == 'Inactive' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                        </select>
                        @error('status') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Previous Balance -->
                    <div>
                        <label for="previous_balance"
                            class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.previous_balance') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">₹</span>
                            </div>
                            <input type="number" step="0.01" name="previous_balance" id="previous_balance"
                                value="{{ old('previous_balance', $customer->previous_balance) }}"
                                class="pl-7 block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all"
                                placeholder="0.00">
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter positive for due amount, negative for advance payment.</p>
                        @error('previous_balance') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Customer Photo -->
                    <div>
                        <label class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.customer_photo') }}</label>
                        <input type="file" name="customer_photo" accept="image/*"
                            class="block w-full text-sm text-gray-500 dark:text-gray-400
                            file:mr-4 file:py-2.5 file:px-4
                            file:rounded-xl file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100
                            dark:file:bg-indigo-900/30 dark:file:text-indigo-400">
                        @error('customer_photo') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="address" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.address') }}</label>
                    <textarea name="address" id="address" rows="3"
                        class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">{{ old('address', $customer->address) }}</textarea>
                    @error('address') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                </div>
                
                <div class="mt-6">
                    <label for="delivery_instructions"
                        class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.delivery_instructions') }}</label>
                    <textarea name="delivery_instructions" id="delivery_instructions" rows="2"
                        class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">{{ old('delivery_instructions', $customer->delivery_instructions) }}</textarea>
                    @error('delivery_instructions') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end items-center gap-3">
                    <a href="{{ route('customers.index') }}"
                        class="px-5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">{{ __('messages.cancel') }}</a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-primary to-blue-600 hover:from-primary-dark hover:to-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all">{{ __('messages.update') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>