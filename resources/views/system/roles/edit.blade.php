<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <a href="{{ route('roles.index') }}" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
                {{ __('messages.edit_role') }}: <span class="font-black text-primary">{{ $role->name }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-xl shadow-gray-200/50 dark:shadow-black/20 rounded-3xl border border-white/40 dark:border-gray-700 overflow-hidden">
            <form method="POST" action="{{ route('roles.update', $role) }}" class="p-8 md:p-10">
                @csrf
                @method('PUT')

                <div class="space-y-8">
                    <div>
                        <label for="name" class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1.5">Role Name <span class="text-red-500">*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name', $role->name) }}" required autofocus
                            class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-bold" />
                        @error('name') <p class="text-sm font-medium text-red-500 mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="permissionTree()" class="pt-4 border-t border-gray-100 dark:border-gray-700">
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Permissions Matrix
                        </label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @php
                                $groupedPermissions = $permissions->groupBy(function($p) {
                                    $parts = explode('_', $p->name);
                                    return isset($parts[1]) ? ucfirst($parts[1]) : 'General';
                                });
                            @endphp
                            
                            @forelse($groupedPermissions as $group => $perms)
                                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm transition-colors hover:border-gray-300 dark:hover:border-gray-600">
                                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 flex items-center justify-between">
                                        <label class="inline-flex items-center cursor-pointer group">
                                            <input type="checkbox" @change="toggleGroup('{{ $group }}')" x-model="groups.{{ $group }}" class="w-5 h-5 rounded border-gray-300 text-primary shadow-sm focus:ring-primary/50 bg-white dark:bg-gray-800 transition-colors">
                                            <span class="ml-3 font-black text-gray-800 dark:text-gray-200 uppercase tracking-wide group-hover:text-primary transition-colors">
                                                {{ $group }}
                                            </span>
                                        </label>
                                    </div>
                                    <div class="p-5 grid grid-cols-1 gap-3">
                                        @foreach($perms as $permission)
                                            <label class="inline-flex items-center cursor-pointer p-2 -m-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800/80 transition-colors">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                    {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}
                                                    x-model="perms" @change="checkGroup('{{ $group }}')" data-group="{{ $group }}"
                                                    class="w-4 h-4 rounded border-gray-300 text-primary shadow-sm focus:ring-primary/50 bg-white dark:bg-gray-800 transition-colors">
                                                <span class="ml-3 text-sm font-medium text-gray-600 dark:text-gray-400 capitalize group-hover:text-gray-900 dark:group-hover:text-gray-200">{{ str_replace('_', ' ', $permission->name) }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full p-4 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-400 rounded-xl text-sm font-medium border border-yellow-200 dark:border-yellow-800">
                                    No specific permissions available yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('permissionTree', () => ({
                            perms: @json($role->permissions->pluck('name')->toArray()),
                            groups: {},
                            init() {
                                document.querySelectorAll('[data-group]').forEach(el => {
                                    const group = el.dataset.group;
                                    if (this.groups[group] === undefined) {
                                        this.checkGroup(group);
                                    }
                                });
                            },
                            toggleGroup(group) {
                                const checkboxes = document.querySelectorAll(`[data-group="${group}"]`);
                                const isChecked = this.groups[group];
                                checkboxes.forEach(cb => {
                                    if (isChecked && !this.perms.includes(cb.value)) {
                                        this.perms.push(cb.value);
                                    } else if (!isChecked && this.perms.includes(cb.value)) {
                                        this.perms = this.perms.filter(v => v !== cb.value);
                                    }
                                });
                            },
                            checkGroup(group) {
                                const checkboxes = Array.from(document.querySelectorAll(`[data-group="${group}"]`));
                                const allChecked = checkboxes.length > 0 && checkboxes.every(cb => this.perms.includes(cb.value));
                                this.groups[group] = allChecked;
                            }
                        }));
                    });
                </script>

                <div class="mt-10 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3">
                    <a href="{{ route('roles.index') }}"
                        class="px-6 py-3 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        {{ __('Cancel') }}
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