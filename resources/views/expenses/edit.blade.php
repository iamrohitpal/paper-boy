<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('expenses.index') }}"
                class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Expense') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mx-auto">
        <form action="{{ route('expenses.update', $expense->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Title /
                        Subject *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $expense->title) }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Category
                        *</label>
                    <select name="category" id="category"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('category', $expense->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Amount (₹)
                        *</label>
                    <input type="number" step="0.01" name="amount" id="amount"
                        value="{{ old('amount', $expense->amount) }}" min="0"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('amount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="expense_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Date
                        *</label>
                    <input type="date" name="expense_date" id="expense_date"
                        value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"
                        required>
                    @error('expense_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Description
                    (Optional)</label>
                <textarea name="description" id="description" rows="3"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">{{ old('description', $expense->description) }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('expenses.index') }}"
                    class="mr-3 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Update
                    Expense</button>
            </div>
        </form>
    </div>
</x-app-layout>