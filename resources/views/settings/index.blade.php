<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('System Settings') }}
            </h2>
        </div>
    </x-slot>

    <div class="mx-auto space-y-6">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm relative dark:bg-green-900/30 dark:text-green-400"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Branding & Theme</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update the application's logo, name, and
                    primary theme color.</p>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- App Name -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="app_name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Application Name</label>
                        <input type="text" name="app_name" id="app_name"
                            value="{{ old('app_name', $setting->app_name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        @error('app_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Primary Color -->
                    <div>
                        <label for="primary_color"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Primary Color</label>
                        <div class="mt-1 flex items-center space-x-3">
                            <input type="color" name="primary_color" id="primary_color"
                                value="{{ old('primary_color', $setting->primary_color) }}"
                                class="h-10 w-14 rounded-md border-gray-300 cursor-pointer shadow-sm focus:border-primary focus:ring-primary dark:bg-gray-700 dark:border-gray-600 p-1">
                            <span class="text-sm text-gray-500 dark:text-gray-400"
                                id="color_hex">{{ old('primary_color', $setting->primary_color) }}</span>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Choose a color that fits your brand.
                            This affects buttons, links, and sidebars.</p>
                        @error('primary_color') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Logo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Brand Logo</label>
                        <div class="mt-1 flex items-center space-x-4">
                            @if($setting->logo_path)
                                <div
                                    class="relative w-16 h-16 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center border border-gray-200 dark:border-gray-600 p-2">
                                    <img src="{{ Storage::url($setting->logo_path) }}" alt="Current Logo"
                                        class="max-h-full max-w-full object-contain">
                                </div>
                            @endif
                            <input type="file" name="logo" id="logo" accept="image/*"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 dark:file:bg-gray-700 dark:file:text-gray-300 cursor-pointer">
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, or SVG up to 2MB. Transparent
                            backgrounds work best.</p>
                        @error('logo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Small script to update hex text live -->
    <script>
        document.getElementById('primary_color').addEventListener('input', function (e) {
            document.getElementById('color_hex').textContent = e.target.value.toUpperCase();
        });
    </script>
</x-app-layout>