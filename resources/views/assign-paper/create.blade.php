<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quick Assign Paper') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mx-auto p-6" x-data="{ 
                    newspaperPrices: {
                        @foreach($newspapers as $np)
                            '{{ $np->id }}': {
                                daily: {{ $np->selling_price ?: 0 }},
                                sunday: {{ $np->selling_price_sunday ?: 'null' }}
                            }{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    },
                    selectedNewspaper: '',
                    currentPrice: 0,
                    currentSundayPrice: '',
                    updatePrice() {
                        if (this.selectedNewspaper && this.newspaperPrices[this.selectedNewspaper]) {
                            const prices = this.newspaperPrices[this.selectedNewspaper];
                            this.currentPrice = prices.daily;
                            this.currentSundayPrice = prices.sunday !== null ? prices.sunday : '';
                        } else {
                            this.currentPrice = 0;
                            this.currentSundayPrice = '';
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
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">1. Select Customer</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="customer_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer
                                    *</label>
                                <select name="customer_id" id="customer_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                    required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->mobile }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="payment_frequency"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer Payment
                                    Frequency *</label>
                                <select name="payment_frequency" id="payment_frequency"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                    required>
                                    <option value="Daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Updates how often this customer pays you.</p>
                                @error('payment_frequency') <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Newspaper Selection -->
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">2. Assign Newspaper</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label for="newspaper_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Newspaper
                                    *</label>
                                <select name="newspaper_id" id="newspaper_id" x-model="selectedNewspaper"
                                    @change="updatePrice()"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md"
                                    required>
                                    <option value="">Select Newspaper</option>
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
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date
                                    *</label>
                                <input type="date" name="start_date" id="start_date" value="{{ date('Y-m-d') }}"
                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    required>
                                @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="quantity"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity
                                    *</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1"
                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    required>
                                @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="price"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price (₹)
                                    *</label>
                                <input type="number" name="price" id="price" step="0.01" min="0" x-model="currentPrice"
                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                    required>
                                <p class="text-xs text-gray-500 mt-1">Base price per day</p>
                                @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="price_sunday"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sunday Price
                                    (₹)</label>
                                <input type="number" name="price_sunday" id="price_sunday" step="0.01" min="0"
                                    x-model="currentSundayPrice"
                                    class="mt-1 focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <p class="text-xs text-gray-500 mt-1">Leave empty if same</p>
                                @error('price_sunday') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Assign Paper
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>