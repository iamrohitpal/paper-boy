<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.profile') }}
        </h2>
    </x-slot>

    <div>
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                role="alert">
                <span class="font-medium">{{ __('messages.success') }}</span> {{ session('success') }}
            </div>
        @endif

        <div
            class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg backdrop-blur-md bg-opacity-95 dark:bg-opacity-95 border border-gray-100 dark:border-gray-700">
            <section>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('messages.profile_information') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('messages.update_profile_info') }}
                    </p>
                </header>

                <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6 max-w-xl">
                    @csrf
                    @method('put')

                    <div>
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.name') }}</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            autofocus autocomplete="name" class="mt-1 block w-full">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.email') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            autocomplete="username" class="mt-1 block w-full">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700 my-6">

                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('messages.language_preferences') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('messages.update_language_pref') }}
                        </p>
                    </header>

                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.language') }}</label>
                        <select name="language" id="language" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600">
                            <option value="en" {{ old('language', $user->language) == 'en' ? 'selected' : '' }}>{{ __('messages.english') }}</option>
                            <option value="hi" {{ old('language', $user->language) == 'hi' ? 'selected' : '' }}>{{ __('messages.hindi') }}</option>
                        </select>
                        @error('language')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700 my-6">

                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('messages.update_password') }}
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('messages.update_password_desc') }}
                        </p>
                    </header>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.new_password') }}</label>
                        <input type="password" name="password" id="password" autocomplete="new-password"
                            class="mt-1 block w-full">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.confirm_password') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            autocomplete="new-password" class="mt-1 block w-full">
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:bg-primary-dark active:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('messages.save') }}
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>