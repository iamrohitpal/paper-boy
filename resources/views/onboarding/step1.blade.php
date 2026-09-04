<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.welcome_to_paper_boy_saas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">{{ __('messages.step_1_confirm_details') }}</h3>
                    <p class="mb-6">{{ __('messages.verify_agency_details') }}</p>

                    <form method="POST" action="{{ route('onboarding.step1') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.agency_name') }}</label>
                            <input type="text" disabled value="{{ auth()->user()->tenant->name ?? 'N/A' }}" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.owner_name') }}</label>
                            <input type="text" disabled value="{{ auth()->user()->name }}" class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="flex justify-between items-center">
                            <button type="submit" formaction="{{ route('onboarding.skip') }}" class="text-sm text-gray-500 hover:text-gray-700">{{ __('messages.skip_onboarding') }}</button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">{{ __('messages.next_step_add_newspaper') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
