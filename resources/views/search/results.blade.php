<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.search_results') }} 
            @if($query)
                <span class="text-gray-500 dark:text-gray-400 font-normal">{{ __('messages.for_query', ['query' => $query]) }}</span>
            @endif
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8">
        @if(empty($query))
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8 text-center border border-gray-100 dark:border-gray-700">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('messages.enter_search_term') }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('messages.search_for_customers') }}</p>
            </div>
        @elseif($results['customers']->isEmpty() && $results['invoices']->isEmpty() && $results['newspapers']->isEmpty())
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8 text-center border border-gray-100 dark:border-gray-700">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('messages.no_results_found') }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('messages.we_couldnt_find_anything', ['query' => $query]) }}</p>
            </div>
        @else
            <div class="space-y-8">
                <!-- Customers Results -->
                @if($results['customers']->isNotEmpty())
                    <section class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="px-4 py-5 border-b border-gray-200 dark:border-gray-700 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                {{ __('messages.customers_count', ['count' => $results['customers']->count()]) }}
                            </h3>
                        </div>
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($results['customers'] as $customer)
                                <li>
                                    <a href="{{ route('customers.show', $customer->id) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <div class="px-4 py-4 sm:px-6 flex justify-between items-center">
                                            <div>
                                                <p class="text-sm font-medium text-primary truncate">{{ $customer->name }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->customer_id }} &bull; {{ $customer->mobile }}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                <!-- Invoices Results -->
                @if($results['invoices']->isNotEmpty())
                    <section class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="px-4 py-5 border-b border-gray-200 dark:border-gray-700 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                {{ __('messages.invoices_count', ['count' => $results['invoices']->count()]) }}
                            </h3>
                        </div>
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($results['invoices'] as $invoice)
                                <li>
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <div class="px-4 py-4 sm:px-6 flex justify-between items-center">
                                            <div>
                                                <p class="text-sm font-medium text-primary truncate">{{ $invoice->invoice_number }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.total_amount', ['amount' => number_format($invoice->total_amount, 2)]) }} &bull; {{ __('messages.status_val', ['status' => $invoice->status]) }}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                <!-- Newspapers Results -->
                @if($results['newspapers']->isNotEmpty())
                    <section class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="px-4 py-5 border-b border-gray-200 dark:border-gray-700 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                {{ __('messages.newspapers_count', ['count' => $results['newspapers']->count()]) }}
                            </h3>
                        </div>
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($results['newspapers'] as $newspaper)
                                <li>
                                    <a href="{{ route('newspapers.edit', $newspaper->id) }}" class="block hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <div class="px-4 py-4 sm:px-6 flex justify-between items-center">
                                            <div>
                                                <p class="text-sm font-medium text-primary truncate">{{ $newspaper->name }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.publisher_val', ['publisher' => $newspaper->publisher]) }}</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
