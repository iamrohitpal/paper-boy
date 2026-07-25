<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daily/Weekly Collections') }}
        </h2>
    </x-slot>

    <div>
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 sm:flex sm:items-center sm:justify-between">
                <div class="flex space-x-4">
                    <a href="{{ route('daily-collections.index', ['type' => 'Daily']) }}" class="px-4 py-2 text-sm font-medium rounded-md {{ $type === 'Daily' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Daily Payers
                    </a>
                    <a href="{{ route('daily-collections.index', ['type' => 'Weekly']) }}" class="px-4 py-2 text-sm font-medium rounded-md {{ $type === 'Weekly' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Weekly Payers
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="m-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('daily-collections.store') }}" method="POST" x-data="{ selectAll: false }">
                @csrf
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <input type="checkbox" x-model="selectAll" @change="document.querySelectorAll('.customer-checkbox').forEach(cb => { if(!cb.disabled) cb.checked = selectAll })" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Active Papers
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Expected Today
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($customers as $customer)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="customer_ids[]" value="{{ $customer->id }}" class="customer-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ $customer->paid_today || $customer->expected_collection == 0 ? 'disabled' : '' }}>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $customer->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $customer->mobile }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-gray-300">
                                            @foreach($customer->subscriptions as $sub)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mr-1 mb-1">
                                                    {{ $sub->newspaper->name }} (Qty: {{ $sub->quantity }})
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">₹{{ number_format($customer->expected_collection, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($customer->expected_collection == 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                No Delivery Today
                                            </span>
                                        @elseif($customer->paid_today)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                Paid
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <p>No {{ $type }} payers found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($customers->where('paid_today', false)->where('expected_collection', '>', 0)->count() > 0)
                    <div class="p-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 sm:flex sm:items-center sm:justify-between">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Select customers and mark them as paid for today's delivery.</p>
                        <button type="submit" class="mt-3 sm:mt-0 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Mark Selected as Paid
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-app-layout>
