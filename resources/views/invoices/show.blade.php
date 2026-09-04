<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <a href="{{ route('invoices.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('{{ __('messages.invoice_details') }}') }}: {{ $invoice->invoice_number }}
                </h2>
            </div>
            
            <div class="flex items-center space-x-3">
                <form action="{{ route('invoices.whatsapp', $invoice->id) }}" method="POST" class="inline-block" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Send WhatsApp bill notification to {{ addslashes($invoice->customer->name) }}?', onConfirm: () => $el.submit() })">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-sm font-medium shadow flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Send WhatsApp
                    </button>
                </form>
                <a href="{{ route('invoices.pdf', $invoice->id) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium shadow flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    {{ __('messages.download_pdf') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden max-w-5xl mx-auto p-6 md:p-8">
        <!-- Invoice Header -->
        <div class="flex flex-col md:flex-row justify-between items-start mb-8 border-b border-gray-200 dark:border-gray-700 pb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('messages.invoice') }}</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">#{{ $invoice->invoice_number }}</p>
                <div class="mt-4">
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                        {{ $invoice->status === 'Paid' ? 'bg-green-100 text-green-800' : 
                           ($invoice->status === 'Partially Paid' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ $invoice->status }}
                    </span>
                </div>
            </div>
            <div class="mt-4 md:mt-0 text-left md:text-right">
                <div class="text-gray-600 dark:text-gray-300">
                    <p><span class="font-semibold">Billing Month:</span> {{ $invoice->billing_month->format('F Y') }}</p>
                    <p><span class="font-semibold">{{ __('messages.issue_date') }}:</span> {{ $invoice->created_at->format('d M, Y') }}</p>
                    <p><span class="font-semibold text-red-500">{{ __('messages.due_date') }}:</span> {{ $invoice->due_date->format('d M, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <div class="flex flex-col md:flex-row justify-between mb-8">
            <div class="mb-6 md:mb-0 w-full md:w-1/2 pr-4">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">{{ __('messages.billed_to') }}</h3>
                <div class="text-gray-800 dark:text-gray-200">
                    <p class="font-bold text-lg">{{ $invoice->customer->name }}</p>
                    <p class="mt-1">{{ $invoice->customer->customer_id }}</p>
                    <p>{{ $invoice->customer->mobile }}</p>
                    @if($invoice->customer->email) <p>{{ $invoice->customer->email }}</p> @endif
                    <p class="mt-2 text-sm">{{ $invoice->customer->address ?? $invoice->customer->area ?? '{{ __('messages.address_not_provided') }}' }}</p>
                </div>
            </div>
            
            <div class="w-full md:w-1/2 md:text-right">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">{{ __('messages.from') }}</h3>
                <div class="text-gray-800 dark:text-gray-200">
                    <p class="font-bold text-lg">PaperBoy Distributions</p>
                    <p class="mt-1">City Center</p>
                    <p>contact@paperboy.com</p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto mb-8 border border-gray-200 dark:border-gray-700 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.qty_days') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.unit_price') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('messages.total') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($invoice->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                {{ $item->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                ₹{{ number_format($item->unit_price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-right">
                                ₹{{ number_format($item->total, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="flex justify-end border-b border-gray-200 dark:border-gray-700 pb-8 mb-8">
            <div class="w-full md:w-1/3">
                <div class="flex justify-between py-2 text-gray-600 dark:text-gray-400">
                    <span>{{ __('messages.subtotal') }}</span>
                    <span>₹{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400">
                    <span>{{ __('messages.amount_paid') }}</span>
                    <span class="text-green-600 dark:text-green-400">- ₹{{ number_format($invoice->paid_amount, 2) }}</span>
                </div>
                <div class="flex justify-between py-3 text-lg font-bold text-gray-900 dark:text-white">
                    <span>{{ __('messages.balance_due') }}</span>
                    <span>₹{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('messages.notes_payment_terms') }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('messages.payment_terms_text') }}
            </p>
        </div>
    </div>
</x-app-layout>
