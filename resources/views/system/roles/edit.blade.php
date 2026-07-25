<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Role') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <form method="POST" action="{{ route('roles.update', $role) }}">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Role
                        Name</label>
                    <input id="name"
                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        type="text" name="name" value="{{ old('name', $role->name) }}" required autofocus />
                    @error('name')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Permissions</label>
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-md p-4 border border-gray-200 dark:border-gray-700">
                        @php
                            $groupedPermissions = $permissions->groupBy(function($p) {
                                $parts = explode('_', $p->name);
                                return isset($parts[1]) ? ucfirst($parts[1]) : 'General';
                            });
                        @endphp
                        
                        @forelse($groupedPermissions as $group => $perms)
                            <div class="mb-4 last:mb-0">
                                <h4 class="font-semibold text-gray-800 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700 pb-1 mb-2">{{ $group }}</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 ml-4">
                                    @foreach($perms as $permission)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                class="rounded dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-900"
                                                {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $permission->name) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <span class="text-sm text-gray-500">No specific permissions available.</span>
                        @endforelse
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        href="{{ route('roles.index') }}">
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