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
                    {{ __('Invoice Details') }}: {{ $invoice->invoice_number }}
                </h2>
            </div>
            
            <a href="{{ route('invoices.pdf', $invoice->id) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium shadow flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download PDF
            </a>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden max-w-5xl mx-auto p-6 md:p-8">
        <!-- Invoice Header -->
        <div class="flex flex-col md:flex-row justify-between items-start mb-8 border-b border-gray-200 dark:border-gray-700 pb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">INVOICE</h1>
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
                    <p><span class="font-semibold">Issue Date:</span> {{ $invoice->created_at->format('d M, Y') }}</p>
                    <p><span class="font-semibold text-red-500">Due Date:</span> {{ $invoice->due_date->format('d M, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <div class="flex flex-col md:flex-row justify-between mb-8">
            <div class="mb-6 md:mb-0 w-full md:w-1/2 pr-4">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Billed To</h3>
                <div class="text-gray-800 dark:text-gray-200">
                    <p class="font-bold text-lg">{{ $invoice->customer->name }}</p>
                    <p class="mt-1">{{ $invoice->customer->customer_id }}</p>
                    <p>{{ $invoice->customer->mobile }}</p>
                    @if($invoice->customer->email) <p>{{ $invoice->customer->email }}</p> @endif
                    <p class="mt-2 text-sm">{{ $invoice->customer->address ?? $invoice->customer->area ?? 'Address not provided' }}</p>
                </div>
            </div>
            
            <div class="w-full md:w-1/2 md:text-right">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">From</h3>
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
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Qty/Days</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Unit Price</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
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
                    <span>Subtotal</span>
                    <span>₹{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400">
                    <span>Amount Paid</span>
                    <span class="text-green-600 dark:text-green-400">- ₹{{ number_format($invoice->paid_amount, 2) }}</span>
                </div>
                <div class="flex justify-between py-3 text-lg font-bold text-gray-900 dark:text-white">
                    <span>Balance Due</span>
                    <span>₹{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Notes / Payment Terms</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Please make the payment by the due date to avoid service interruption. Payments can be made via Cash or UPI.
            </p>
        </div>
    </div>
</x-app-layout>
