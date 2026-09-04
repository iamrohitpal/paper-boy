<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                {{ __('messages.customers') }}
            </h2>
            @if($canAddCustomer)
                <a href="{{ route('customers.create') }}"
                    class="px-5 py-2.5 bg-gradient-to-r from-primary to-blue-600 hover:from-primary-dark hover:to-blue-700 text-white rounded-xl text-sm font-semibold shadow-lg shadow-blue-500/30 transform hover:scale-105 hover:shadow-blue-500/50 transition-all duration-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('messages.add_customer') }}
                </a>
            @else
                <button disabled
                    class="px-5 py-2.5 bg-gray-400 text-white rounded-xl text-sm font-semibold shadow cursor-not-allowed flex items-center gap-2"
                    title="Customer limit reached">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                    {{ __('messages.add_customer_limit') }}
                </button>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6" x-data="{
        selectedCustomers: [],
        selectAll: false,
        billDate: '{{ date('Y-m-d') }}',
        toggleAll() {
            if (this.selectAll) {
                this.selectedCustomers = [{{ implode(',', $customers->pluck('id')->toArray()) }}];
            } else {
                this.selectedCustomers = [];
            }
        },
        deselectAll() {
            this.selectAll = false;
            this.selectedCustomers = [];
        },
        submitBulk(actionName, confirmMsg) {
            if (this.selectedCustomers.length === 0) return;
            
            $dispatch('open-confirm', {
                message: confirmMsg,
                onConfirm: () => {
                    const form = document.getElementById('bulkForm');
                    const actionInput = document.getElementById('bulkActionInput');
                    actionInput.value = actionName;
                    form.submit();
                }
            });
        }
    }">
        <form action="{{ route('customers.bulk-actions') }}" method="POST" id="bulkForm">
            @csrf
            <input type="hidden" name="action" id="bulkActionInput" value="">

            <div
                class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">

                <!-- Normal Filter Bar (When no items selected) -->
                <div x-show="selectedCustomers.length === 0"
                    class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                    <div class="flex w-full md:max-w-md relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="{{ __('messages.search_customers') }}"
                            class="pl-10 w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-inner sm:text-sm transition-all duration-300">
                    </div>
                </div>

                <!-- Floating/Overlay Sky-Blue Bulk Actions Action Bar (Shown when items selected) -->
                <div x-show="selectedCustomers.length > 0" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="text-white px-6 py-3.5 flex flex-wrap items-center justify-between gap-4 shadow-md rounded-t-2xl"
                     style="background-color: #009beb;">

                    <div class="flex items-center gap-6">
                        <span class="font-bold text-sm text-white drop-shadow-sm tracking-wide border-r border-white/30 pr-6"
                            x-text="selectedCustomers.length + ' Items Selected'"></span>

                        <!-- Action Group 1: Generate Bill -->
                        <div class="flex items-center gap-2">
                            <input type="date" name="bill_date" x-model="billDate"
                                class="text-xs bg-white text-gray-800 border-0 rounded-lg px-3 py-1.5 font-medium shadow-sm focus:ring-2 focus:ring-white">
                            <button type="button"
                                @click="submitBulk('generate_bills', 'Are you sure you want to generate bills for ' + selectedCustomers.length + ' customer(s) up to ' + billDate + '?')"
                                class="px-4 py-1.5 bg-white/20 hover:bg-white/30 text-white rounded-lg text-xs font-semibold border border-white/40 transition-colors">
                                Apply Bill
                            </button>
                        </div>

                        <!-- Action Group 2: WhatsApp Messages -->
                        <div class="flex items-center gap-2 border-l border-white/30 pl-6">
                            <button type="button"
                                @click="submitBulk('send_whatsapp_bills', 'Are you sure you want to send WhatsApp bill notifications to ' + selectedCustomers.length + ' customer(s)?')"
                                class="px-4 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-semibold shadow transition-colors flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                                Send WhatsApp Bills
                            </button>
                        </div>
                    </div>

                    <!-- Deselect All Button -->
                    <button type="button" @click="deselectAll()"
                        class="text-xs font-semibold text-white/90 hover:text-white hover:underline transition-all">
                        Deselect All
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-white/50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-4 py-4 text-left">
                                    <input type="checkbox" x-model="selectAll" @change="toggleAll()"
                                        class="rounded border-gray-300 dark:border-gray-700 text-primary focus:ring-primary">
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('messages.customer_info') }}</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('messages.contact') }}</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('messages.area') }}</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('messages.status') }}</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-transparent">
                            @forelse ($customers as $customer)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/80 transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="customer_ids[]" value="{{ $customer->id }}"
                                            x-model="selectedCustomers"
                                            class="rounded border-gray-300 dark:border-gray-700 text-primary focus:ring-primary">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 relative">
                                                @if($customer->customer_photo)
                                                    <img class="h-10 w-10 rounded-full object-cover shadow-sm border border-gray-200 dark:border-gray-700"
                                                        src="{{ asset('storage/' . $customer->customer_photo) }}" alt="">
                                                @else
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-900/50 dark:to-blue-900/50 flex items-center justify-center text-indigo-700 dark:text-indigo-400 font-bold border border-indigo-200 dark:border-indigo-800/50 shadow-sm text-lg">
                                                        {{ substr($customer->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $customer->name }}</div>
                                                <div class="text-xs font-medium text-primary mt-0.5">
                                                    {{ $customer->customer_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            class="text-sm font-medium text-gray-900 dark:text-gray-300 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            {{ $customer->mobile }}
                                        </div>
                                        @if($customer->email)
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate max-w-[150px]">
                                                {{ $customer->email }}</div>
                                        @endif
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-400 font-medium">
                                        {{ $customer->area ?? __('messages.n_a') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-3 py-1 inline-flex text-xs font-bold rounded-full {{ $customer->status === 'Active' ? 'bg-green-100/80 text-green-700 dark:bg-green-900/50 dark:text-green-400 border border-green-200 dark:border-green-800' : 'bg-red-100/80 text-red-700 dark:bg-red-900/50 dark:text-red-400 border border-red-200 dark:border-red-800' }}">
                                            {{ $customer->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button type="button"
                                                @click="selectedCustomers = [{{ $customer->id }}]; submitBulk('send_whatsapp_bills', 'Are you sure you want to send WhatsApp bill notification to {{ $customer->name }}?')"
                                                class="p-1.5 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 bg-green-50 hover:bg-green-100 dark:bg-green-900/30 dark:hover:bg-green-900/50 rounded-lg transition-colors"
                                                title="Send WhatsApp Bill">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                                </svg>
                                            </button>
                                            <a href="{{ route('customers.show', $customer->id) }}"
                                                class="p-1.5 text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:hover:bg-emerald-900/50 rounded-lg transition-colors flex items-center gap-1.5 px-3"
                                                title="{{ __('messages.ledger') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                <span class="hidden sm:inline">{{ __('messages.ledger') }}</span>
                                            </a>
                                            <a href="{{ route('customers.edit', $customer->id) }}"
                                                class="p-1.5 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 rounded-lg transition-colors"
                                                title="{{ __('messages.edit') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="h-16 w-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400 text-base font-medium">
                                                {{ __('messages.no_customers') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($customers->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>
        </form>
    </div>
</x-app-layout>