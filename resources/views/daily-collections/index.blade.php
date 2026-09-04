<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ __('messages.daily_weekly_collections') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 rounded-2xl border border-white/40 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between bg-gray-50/50 dark:bg-gray-800/50 gap-4">
                <div class="flex space-x-2 bg-gray-200/50 dark:bg-gray-900/50 p-1.5 rounded-xl self-start sm:self-auto">
                    <a href="{{ route('daily-collections.index', ['type' => 'Daily']) }}" class="px-5 py-2 text-sm font-semibold rounded-lg transition-all duration-300 {{ $type === 'Daily' ? 'bg-white dark:bg-gray-700 text-primary dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-800/50' }}">
                        {{ __('messages.daily_payers') }}
                    </a>
                    <a href="{{ route('daily-collections.index', ['type' => 'Weekly']) }}" class="px-5 py-2 text-sm font-semibold rounded-lg transition-all duration-300 {{ $type === 'Weekly' ? 'bg-white dark:bg-gray-700 text-primary dark:text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-800/50' }}">
                        {{ __('messages.weekly_payers') }}
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="m-6 bg-emerald-50/80 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl shadow-sm flex items-center gap-3" role="alert">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('daily-collections.store') }}" method="POST" x-data="{ selectAll: false }">
                @csrf
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-white/50 dark:bg-gray-900/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left">
                                    <div class="flex items-center">
                                        <input type="checkbox" x-model="selectAll" @change="document.querySelectorAll('.customer-checkbox').forEach(cb => { if(!cb.disabled) cb.checked = selectAll })" class="rounded-md border-gray-300 dark:border-gray-600 text-primary focus:ring-primary dark:bg-gray-800 h-4 w-4 cursor-pointer">
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('messages.customer') }}
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('messages.active_papers') }}
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('messages.expected_today') }}
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('messages.status') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-transparent">
                            @forelse($customers as $customer)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/80 transition-colors {{ $customer->paid_today || $customer->expected_collection == 0 ? 'opacity-75 bg-gray-50/30 dark:bg-gray-800/30' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="customer_ids[]" value="{{ $customer->id }}" class="customer-checkbox rounded-md border-gray-300 dark:border-gray-600 text-primary focus:ring-primary dark:bg-gray-800 h-4 w-4 {{ $customer->paid_today || $customer->expected_collection == 0 ? 'cursor-not-allowed bg-gray-100 dark:bg-gray-700 opacity-50' : 'cursor-pointer' }}" {{ $customer->paid_today || $customer->expected_collection == 0 ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $customer->name }}</div>
                                        <div class="text-xs font-medium text-gray-500 flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $customer->mobile }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($customer->subscriptions as $sub)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50/80 text-blue-700 border border-blue-100 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-800/50 shadow-sm">
                                                    {{ $sub->newspaper->name }} ({{ __('messages.qty') }}: {{ $sub->quantity }})
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-base font-bold {{ $customer->expected_collection > 0 ? 'text-primary' : 'text-gray-400 dark:text-gray-500' }}">₹{{ number_format($customer->expected_collection, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($customer->expected_collection == 0)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100/80 text-gray-600 border border-gray-200 dark:bg-gray-800/80 dark:text-gray-400 dark:border-gray-700 shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                {{ __('messages.no_delivery_today') }}
                                            </span>
                                        @elseif($customer->paid_today)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100/80 text-green-700 border border-green-200 dark:bg-green-900/40 dark:text-green-400 dark:border-green-800/50 shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                {{ __('messages.paid') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100/80 text-yellow-700 border border-yellow-200 dark:bg-yellow-900/40 dark:text-yellow-400 dark:border-yellow-800/50 shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ __('messages.pending') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="h-16 w-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400 text-base font-medium">{{ __('messages.no_payers_found', ['type' => $type]) }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($customers->where('paid_today', false)->where('expected_collection', '>', 0)->count() > 0)
                    <div class="p-6 bg-white/50 dark:bg-gray-900/30 backdrop-blur-md border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ __('messages.select_customers_mark_paid') }}
                        </p>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-green-500/30 transform hover:-translate-y-0.5 transition-all w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('messages.mark_selected_as_paid') }}
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-app-layout>