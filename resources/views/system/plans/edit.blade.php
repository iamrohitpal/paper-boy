<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('plans.index') }}" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
                {{ __('Edit Plan') }}: <span class="font-black text-primary">{{ $plan->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 rounded-3xl border border-white/40 dark:border-gray-700 overflow-hidden">
            <form action="{{ route('plans.update', $plan->id) }}" method="POST" class="p-8 md:p-10">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Core Details Section -->
                    <div class="md:col-span-2 mb-2">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Core Details
                        </h3>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">Plan Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $plan->name) }}" required
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-bold" />
                        @error('name') <p class="text-red-500 text-sm font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">Slug <span class="text-xs font-normal text-gray-500 ml-1">(e.g. starter, pro)</span> <span class="text-red-500">*</span></label>
                        <input type="text" name="slug" value="{{ old('slug', $plan->slug) }}" required
                            class="block w-full bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-mono" />
                        @error('slug') <p class="text-red-500 text-sm font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.description') }}</label>
                        <textarea name="description" rows="3"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all">{{ old('description', $plan->description) }}</textarea>
                    </div>

                    <!-- Pricing & Limits Section -->
                    <div class="md:col-span-2 mt-4 mb-2">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Pricing & Limits
                        </h3>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">Price (₹) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">₹</span>
                            </div>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $plan->price) }}" required
                                class="pl-9 block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-black text-xl text-primary" />
                        </div>
                        @error('price') <p class="text-red-500 text-sm font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">Billing Interval</label>
                        <select name="billing_interval"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-bold">
                            <option value="monthly" {{ old('billing_interval', $plan->billing_interval) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('billing_interval', $plan->billing_interval) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">Customer Limit <span class="text-xs font-normal text-gray-500 ml-1">(Leave blank for unlimited)</span></label>
                        <input type="number" name="customer_limit" value="{{ old('customer_limit', $plan->customer_limit) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all" />
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">Trial Days <span class="text-xs font-normal text-gray-500 ml-1">(Optional)</span></label>
                        <input type="number" name="trial_days" value="{{ old('trial_days', $plan->trial_days) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all" />
                    </div>

                    <div class="md:col-span-2 mt-4 mb-2">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Features & Status
                        </h3>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">Features <span class="text-xs font-normal text-gray-500 ml-1">(Comma separated)</span></label>
                        <input type="text" name="features" value="{{ old('features', is_array($plan->features) ? implode(', ', $plan->features) : '') }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">Status</label>
                        <select name="status"
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-bold">
                            <option value="active" {{ old('status', $plan->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $plan->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                    <a href="{{ route('plans.index') }}"
                        class="px-6 py-3 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-primary to-blue-600 hover:from-primary-dark hover:to-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all">
                        Update Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>