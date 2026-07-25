<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Subscriptions') }}
            </h2>
            <a href="{{ route('subscriptions.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium shadow transition-colors">
                + Add Subscription
            </a>
        </div>
    </x-slot>

    <!-- Alpine component for Bulk Actions -->
    <div x-data="{ 
            selected: [], 
            selectAll: false,
            action: '',
            statusValue: '',
            newspaperValue: '',
            showConfirmModal: false,
            toggleAll() {
                if (this.selectAll) {
                    this.selected = {{ json_encode($subscriptions->pluck('id')) }};
                } else {
                    this.selected = [];
                }
            },
            triggerAction(type) {
                this.action = type;
                if(type === 'status' && !this.statusValue) return alert('Please select a status');
                if(type === 'newspaper' && !this.newspaperValue) return alert('Please select a newspaper');
                this.showConfirmModal = true;
            }
        }">
        
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
            <!-- Header bar with search and bulk actions -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <!-- Search -->
                <form action="{{ route('subscriptions.index') }}" method="GET" class="flex w-full sm:w-auto sm:max-w-md">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search customers..." class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary rounded-l-md shadow-sm sm:text-sm">
                    <button type="submit" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-r-md border border-l-0 border-gray-300 dark:border-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Search
                    </button>
                </form>

                <!-- Bulk Action Bar (shows when items are selected) -->
                <div x-show="selected.length > 0" x-transition.opacity class="flex items-center space-x-3 bg-indigo-50 dark:bg-indigo-900/30 p-2 rounded-lg border border-indigo-100 dark:border-indigo-800" style="display: none;">
                    <span class="text-sm font-medium text-indigo-800 dark:text-indigo-300 px-2">
                        <span x-text="selected.length"></span> selected
                    </span>
                    
                    <!-- Update Status -->
                    <div class="flex items-center space-x-1 border-l border-indigo-200 dark:border-indigo-700 pl-3">
                        <select x-model="statusValue" class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm py-1.5 focus:ring-primary focus:border-primary">
                            <option value="">Update Status...</option>
                            <option value="Active">Active</option>
                            <option value="Paused">Paused</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        <button @click="triggerAction('status')" type="button" class="px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 shadow-sm transition-colors">Apply</button>
                    </div>

                    <!-- Change Newspaper -->
                    <div class="flex items-center space-x-1 border-l border-indigo-200 dark:border-indigo-700 pl-3">
                        <select x-model="newspaperValue" class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm py-1.5 focus:ring-primary focus:border-primary w-48">
                            <option value="">Change Newspaper...</option>
                            @foreach($newspapers ?? [] as $np)
                                <option value="{{ $np->id }}">{{ $np->name }}</option>
                            @endforeach
                        </select>
                        <button @click="triggerAction('newspaper')" type="button" class="px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 shadow-sm transition-colors">Apply</button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">
                                <input type="checkbox" x-model="selectAll" @change="toggleAll" class="rounded border-gray-300 text-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Newspaper</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Schedule</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price (Qty)</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($subscriptions as $subscription)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" :class="{'bg-indigo-50/50 dark:bg-indigo-900/20': selected.includes({{ $subscription->id }})}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" value="{{ $subscription->id }}" x-model="selected" class="rounded border-gray-300 text-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $subscription->customer->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $subscription->customer->customer_id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-300">{{ $subscription->newspaper->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $subscription->newspaper->language }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div>{{ $subscription->delivery_days }}</div>
                                    @if($subscription->delivery_days === 'Custom' && $subscription->custom_days)
                                        <div class="text-xs text-gray-400">{{ implode(', ', $subscription->custom_days) }}</div>
                                    @endif
                                    <div class="text-xs text-gray-400 mt-1">From: {{ $subscription->start_date->format('d M, Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div class="font-medium text-gray-900 dark:text-gray-300">₹{{ number_format($subscription->price, 2) }}</div>
                                    <div class="text-xs">Qty: {{ $subscription->quantity }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full border 
                                        {{ $subscription->status === 'Active' ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800' : 
                                           ($subscription->status === 'Paused' ? 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-800' : 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800') }}">
                                        {{ $subscription->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('subscriptions.edit', $subscription->id) }}" class="text-primary hover:text-primary-dark dark:hover:text-indigo-400 mr-3 transition-colors">Edit</a>
                                    <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST" class="inline-block" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this subscription?', onConfirm: () => $el.submit() })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400 transition-colors">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-200">No subscriptions found</p>
                                        <p class="text-gray-500 dark:text-gray-400 mb-4 mt-1">Get started by creating a new subscription.</p>
                                        <a href="{{ route('subscriptions.create') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">Add Subscription</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($subscriptions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    {{ $subscriptions->links() }}
                </div>
            @endif
        </div>

        <!-- Hidden Bulk Form to submit to server -->
        <form x-ref="bulkForm" method="POST" action="{{ route('subscriptions.bulk-update') }}" class="hidden">
            @csrf
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="selected[]" :value="id">
            </template>
            <input type="hidden" name="action" :value="action">
            <input type="hidden" name="status" :value="statusValue">
            <input type="hidden" name="newspaper_id" :value="newspaperValue">
        </form>

        <!-- Alpine Modal for Confirmation -->
        <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showConfirmModal" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500/75 dark:bg-gray-900/90 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showConfirmModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                    
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900/50 sm:mx-0 sm:h-10 sm:w-10 text-yellow-600 dark:text-yellow-400">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Confirm Bulk Update
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to update <span class="font-bold text-gray-900 dark:text-white" x-text="selected.length"></span> subscriptions? 
                                        This action will immediately change their records in the system.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="$refs.bulkForm.submit()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition-colors dark:focus:ring-offset-gray-900">
                            Confirm Update
                        </button>
                        <button type="button" @click="showConfirmModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
