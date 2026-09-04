<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('users.index') }}"
                class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
                {{ __('messages.edit_user') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 rounded-3xl border border-white/40 dark:border-gray-700 overflow-hidden">
            <form method="POST" action="{{ route('users.update', $user) }}" class="p-8 md:p-10">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.name') }} <span class="text-red-500">*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all" />
                        @error('name') <p class="text-sm font-medium text-red-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.email') }} <span class="text-red-500">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all" />
                        @error('email') <p class="text-sm font-medium text-red-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">
                                Password <span class="text-xs font-medium text-gray-500 font-normal ml-1">(leave blank to keep current)</span>
                            </label>
                            <input id="password" type="password" name="password"
                                class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all" />
                            @error('password') <p class="text-sm font-medium text-red-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.confirm_password') }}</label>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all" />
                            @error('password_confirmation') <p class="text-sm font-medium text-red-500 mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-700">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-4">{{ __('messages.assign_roles') }}</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($roles as $role)
                                <label class="flex items-center p-3 {{ in_array($role->name, $userRoles) ? 'bg-indigo-50 border-indigo-200 dark:bg-indigo-900/30 dark:border-indigo-800' : 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700' }} border rounded-xl cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:border-indigo-200 dark:hover:border-indigo-800 transition-colors shadow-sm group">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                        class="w-5 h-5 rounded border-gray-300 text-primary shadow-sm focus:ring-primary/50 bg-white dark:bg-gray-800 transition-colors"
                                        {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                    <span class="ml-3 text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-primary dark:group-hover:text-indigo-400">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                    <a href="{{ route('users.index') }}"
                        class="px-6 py-3 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-primary to-blue-600 hover:from-primary-dark hover:to-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>