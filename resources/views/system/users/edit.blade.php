<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Name</label>
                    <input id="name"
                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus />
                    @error('name')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                    <input id="email"
                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        type="email" name="email" value="{{ old('email', $user->email) }}" required />
                    @error('email')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Password
                        (leave blank to keep current)</label>
                    <input id="password"
                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        type="password" name="password" />
                    @error('password')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="password_confirmation"
                        class="block font-medium text-sm text-gray-700 dark:text-gray-300">Confirm Password</label>
                    <input id="password_confirmation"
                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        type="password" name="password_confirmation" />
                    @error('password_confirmation')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Assign Roles</label>
                    <div class="mt-2 space-y-2">
                        @foreach($roles as $role)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                    {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        href="{{ route('users.index') }}">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                        class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>