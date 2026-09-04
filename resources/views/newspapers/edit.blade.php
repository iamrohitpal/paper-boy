<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('newspapers.index') }}"
                class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
                {{ __('messages.edit_newspaper') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 rounded-2xl border border-white/40 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('newspapers.update', $newspaper->id) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.newspaper_name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $newspaper->name) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all"
                            required>
                        @error('name') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Publisher -->
                    <div>
                        <label for="publisher"
                            class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.publisher') }}</label>
                        <input type="text" name="publisher" id="publisher" value="{{ old('publisher', $newspaper->publisher) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">
                        @error('publisher') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Language -->
                    <div>
                        <label for="language" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.language') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="language" id="language" value="{{ old('language', $newspaper->language) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all"
                            required>
                        @error('language') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- MRP -->
                    <div>
                        <label for="mrp" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.mrp') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">₹</span>
                            </div>
                            <input type="number" step="0.01" name="mrp" id="mrp" value="{{ old('mrp', $newspaper->mrp) }}"
                                class="pl-7 block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all"
                                required>
                        </div>
                        @error('mrp') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Purchase Price -->
                    <div>
                        <label for="purchase_price"
                            class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.purchase_price') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">₹</span>
                            </div>
                            <input type="number" step="0.01" name="purchase_price" id="purchase_price"
                                value="{{ old('purchase_price', $newspaper->purchase_price) }}"
                                class="pl-7 block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all"
                                required>
                        </div>
                        @error('purchase_price') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Selling Price (Weekday/Daily) -->
                    <div>
                        <label for="selling_price"
                            class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.selling_price_daily') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">₹</span>
                            </div>
                            <input type="number" step="0.01" name="selling_price" id="selling_price"
                                value="{{ old('selling_price', $newspaper->selling_price) }}"
                                class="pl-7 block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all"
                                required>
                        </div>
                        @error('selling_price') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Selling Price (Sunday) -->
                    <div>
                        <label for="selling_price_sunday"
                            class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.selling_price_sunday') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">₹</span>
                            </div>
                            <input type="number" step="0.01" name="selling_price_sunday" id="selling_price_sunday"
                                value="{{ old('selling_price_sunday', $newspaper->selling_price_sunday) }}"
                                class="pl-7 block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all"
                                placeholder="{{ __('messages.leave_empty_same_as_daily') }}">
                        </div>
                        @error('selling_price_sunday') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Commission -->
                    <div>
                        <label for="commission"
                            class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.commission') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">₹</span>
                            </div>
                            <input type="number" step="0.01" name="commission" id="commission"
                                value="{{ old('commission', $newspaper->commission) }}"
                                class="pl-7 block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">
                        </div>
                        @error('commission') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label for="status" class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.status') }} <span class="text-red-500">*</span></label>
                        <select name="status" id="status"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">
                            <option value="Active" {{ old('status', $newspaper->status) == 'Active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                            <option value="Inactive" {{ old('status', $newspaper->status) == 'Inactive' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                        </select>
                        @error('status') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description"
                        class="block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.description') }}</label>
                    <textarea name="description" id="description" rows="3"
                        class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">{{ old('description', $newspaper->description) }}</textarea>
                    @error('description') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end items-center gap-3">
                    <a href="{{ route('newspapers.index') }}"
                        class="px-5 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">{{ __('messages.cancel') }}</a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-primary to-blue-600 hover:from-primary-dark hover:to-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all">{{ __('messages.update') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>