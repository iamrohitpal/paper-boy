<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('{{ __('messages.quick_assign_paper') }}') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mx-auto p-6" x-data="{ 
                    newspaperPrices: {
                        @foreach($newspapers as $np)
                            '{{ $np->id }}': {
                                daily: {{ $np->selling_price ?: 0 }},
                                monday: {{ $np->selling_price_monday ?: 'null' }},
                                tuesday: {{ $np->selling_price_tuesday ?: 'null' }},
                                wednesday: {{ $np->selling_price_wednesday ?: 'null' }},
                                thursday: {{ $np->selling_price_thursday ?: 'null' }},
                                friday: {{ $np->selling_price_friday ?: 'null' }},
                                saturday: {{ $np->selling_price_saturday ?: 'null' }},
                                sunday: {{ $np->selling_price_sunday ?: 'null' }}
                            }{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    },
                    selectedNewspaper: '',
                    currentPrice: 0,
                    currentDayPrices: { monday: '', tuesday: '', wednesday: '', thursday: '', friday: '', saturday: '', sunday: '' },
                    showDayPrices: false,
                    updatePrice() {
                        if (this.selectedNewspaper && this.newspaperPrices[this.selectedNewspaper]) {
                            const prices = this.newspaperPrices[this.selectedNewspaper];
                            this.currentPrice = prices.daily;
                            const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                            days.forEach(day => {
                                this.currentDayPrices[day] = prices[day] !== null ? prices[day] : '';
                            });
                            this.showDayPrices = days.some(day => prices[day] !== null);
                        } else {
                            this.currentPrice = 0;
                            this.currentDayPrices = { monday: '', tuesday: '', wednesday: '', thursday: '', friday: '', saturday: '', sunday: '' };
                            this.showDayPrices = false;
                        }
                    }
                }">

            @if (session('success'))
                <div class="mb-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('assign-paper.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Customer Selection -->
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('messages.step_1_select_customer') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="customer_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.customer') }} *</label>
                                <select name="customer_id" id="customer_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                    required>
                                    <option value="">{{ __('messages.select_a_customer') }}</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->mobile }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="payment_frequency"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.customer_payment_frequency') }} *</label>
                                <select name="payment_frequency" id="payment_frequency"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                    required>
                                    <option value="Daily">{{ __('messages.daily') }}</option>
                                    <option value="Weekly">{{ __('messages.weekly') }}</option>
                                    <option value="Monthly">{{ __('messages.monthly') }}</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">{{ __('messages.updates_how_often_customer_pays') }}</p>
                                @error('payment_frequency') <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Newspaper Selection -->
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('messages.step_2_assign_newspaper') }}</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label for="newspaper_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.newspaper') }} *</label>
                                <select name="newspaper_id" id="newspaper_id" x-model="selectedNewspaper"
                                    @change="updatePrice()"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                    required>
                                    <option value="">{{ __('messages.select_a_newspaper') }}</option>
                                    @foreach($newspapers as $newspaper)
                                        <option value="{{ $newspaper->id }}">{{ $newspaper->name }}</option>
                                    @endforeach
                                </select>
                                @error('newspaper_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label for="start_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.start_date') }} *</label>
                                <input type="date" name="start_date" id="start_date" value="{{ date('Y-m-d') }}"
                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    required>
                                @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="quantity"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.quantity') }} *</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1"
                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    required>
                                @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="price"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.price_rs') }} *</label>
                                <input type="number" name="price" id="price" step="0.01" min="0" x-model="currentPrice"
                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    required>
                                <p class="text-xs text-gray-500 mt-1">{{ __('messages.base_price_per_day') }}</p>
                                @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex items-end">
                                <button type="button" @click="showDayPrices = !showDayPrices"
                                    class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="h-4 w-4 transition-transform" :class="showDayPrices ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <span x-text="showDayPrices ? '{{ __("messages.hide_day_prices") }}' : '{{ __("messages.show_day_prices") }}'"></span>
                                </button>
                            </div>
                        </div>

                        <div x-show="showDayPrices" x-collapse class="mt-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ __('messages.daywise_pricing_hint') }}</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200 dark:border-gray-600">
                                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                <div>
                                    <label for="price_{{ $day }}"
                                        class="block font-medium text-xs text-gray-600 dark:text-gray-400 mb-1">{{ __('messages.' . $day) }}</label>
                                    <input type="number" name="price_{{ $day }}" id="price_{{ $day }}" step="0.01" min="0"
                                        x-model="currentDayPrices.{{ $day }}"
                                        class="block w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary focus:border-primary rounded-md"
                                        placeholder="{{ __('messages.default_price') }}">
                                    @error('price_' . $day) <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            {{ __('messages.assign_paper') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>