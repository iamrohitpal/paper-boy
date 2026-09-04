<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ __('messages.system_settings') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        @if (session('success'))
            <div class="bg-emerald-100/80 backdrop-blur-sm border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl shadow-sm relative dark:bg-emerald-900/30 dark:text-emerald-400" role="alert">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- WhatsApp Integration Section -->
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-lg border border-white/40 dark:border-gray-700 overflow-hidden mb-6 transition-all hover:shadow-xl">
            <div class="px-6 py-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-800 dark:to-gray-800 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-100 dark:bg-green-900/40 rounded-xl">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.whatsapp_integration') }}</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-medium">{{ __('messages.whatsapp_integration_desc') }}</p>
                    </div>
                </div>
                <div class="w-full md:w-auto mt-4 md:mt-0">
                    <a href="{{ route('whatsapp.index') }}" class="w-full md:w-auto inline-flex justify-center items-center px-6 py-2.5 rounded-xl text-sm font-bold shadow-md transform hover:-translate-y-0.5 transition-all
                        bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-green-300 dark:hover:border-green-600 hover:text-green-600 dark:hover:text-green-400">
                        {{ __('messages.whatsapp_settings') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Subscription Section -->
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-lg border border-white/40 dark:border-gray-700 overflow-hidden transition-all hover:shadow-xl">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center gap-3">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.your_subscription') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-medium">{{ __('messages.your_subscription_desc') }}</p>
                </div>
            </div>
            
            <div class="p-6">
                <div class="mb-8 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden">
                    <!-- Background subtle pattern -->
                    <div class="absolute right-0 top-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none">
                        <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                    </div>
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-6">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wider font-bold mb-1">{{ __('messages.current_plan') }}</p>
                                <h4 class="text-2xl font-black text-gray-900 dark:text-white">{{ $activeSubscription ? $activeSubscription->plan->name : __('messages.none') }}</h4>
                            </div>
                            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 px-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                <span class="text-sm font-bold text-gray-500 dark:text-gray-400">{{ __('messages.status') }}:</span>
                                <span class="uppercase font-black {{ $activeSubscription && $activeSubscription->status === 'active' ? 'text-emerald-500' : 'text-amber-500' }}">{{ $activeSubscription->status ?? 'N/A' }}</span>
                            </div>
                        </div>
                        
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm">
                            <div class="mb-3 flex justify-between items-end">
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    {{ __('messages.customer_quota') }}
                                </span>
                                <span class="text-base font-black {{ $usedCustomers >= $customerLimit ? 'text-rose-500' : 'text-gray-900 dark:text-white' }}">
                                    {{ $usedCustomers }} <span class="text-sm font-medium text-gray-500">/ {{ $customerLimit === PHP_INT_MAX ? __('messages.unlimited') : $customerLimit }}</span>
                                </span>
                            </div>
                            @php 
                                $percent = $customerLimit === PHP_INT_MAX ? 0 : min(100, round(($usedCustomers / max(1, $customerLimit)) * 100)); 
                                $color = $percent >= 100 ? 'bg-rose-500' : ($percent >= 80 ? 'bg-amber-500' : 'bg-primary');
                            @endphp
                            <div class="w-full bg-gray-100 rounded-full h-3.5 dark:bg-gray-700 overflow-hidden shadow-inner">
                                <div class="{{ $color }} h-3.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 mb-6">
                    <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
                    <h4 class="font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-sm">{{ __('messages.available_upgrade_plans') }}</h4>
                    <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($availablePlans as $plan)
                        <div class="relative bg-white dark:bg-gray-800 rounded-2xl p-6 border-2 transition-all duration-300 {{ $activeSubscription && $activeSubscription->plan_id == $plan->id ? 'border-primary shadow-lg shadow-primary/20 scale-100 md:scale-105 z-10' : 'border-gray-100 dark:border-gray-700 shadow-sm hover:border-gray-300 dark:hover:border-gray-500 hover:shadow-md' }}">
                            @if($activeSubscription && $activeSubscription->plan_id == $plan->id)
                                <div class="absolute top-0 right-0 -mt-3 -mr-3 bg-primary text-white text-xs font-bold px-3 py-1 rounded-full shadow-md uppercase tracking-wide">Current</div>
                            @endif
                            <h5 class="font-black text-xl text-gray-900 dark:text-white mb-2">{{ $plan->name }}</h5>
                            <div class="flex items-baseline mb-6">
                                <span class="text-3xl font-black text-gray-900 dark:text-white">₹{{ $plan->price }}</span>
                                <span class="text-sm font-bold text-gray-500 ml-1">/{{ $plan->billing_interval }}</span>
                            </div>
                            
                            <div class="space-y-3 mb-8">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-emerald-500 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $plan->customer_limit ? $plan->customer_limit .  ' ' . __('messages.customers') : __('messages.unlimited_customers') }}</span>
                                </div>
                                @if($plan->features)
                                    @foreach($plan->features as $feature)
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-emerald-500 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ ucfirst($feature) }}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            @if($activeSubscription && $activeSubscription->plan_id == $plan->id)
                                <button disabled class="w-full bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 py-3 rounded-xl text-sm font-bold cursor-not-allowed border border-gray-200 dark:border-gray-600">Active Plan</button>
                            @else
                                <form action="{{ route('settings.upgrade', $plan->id) }}" method="POST">
                                    @csrf
                                    <button class="w-full bg-gradient-to-r from-gray-800 to-gray-900 hover:from-black hover:to-black dark:from-gray-600 dark:to-gray-700 dark:hover:from-gray-500 dark:hover:to-gray-600 text-white py-3 rounded-xl text-sm font-bold shadow-md transform hover:-translate-y-0.5 transition-all">{{ __('messages.select_plan') }}</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if(auth()->user()->hasRole('Super Admin') && !session()->has('active_tenant_id'))
        <!-- Global Branding Section -->
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-2xl shadow-lg border border-white/40 dark:border-gray-700 overflow-hidden transition-all hover:shadow-xl">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center gap-3">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('messages.global_branding_theme') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 font-medium">{{ __('messages.global_branding_theme_desc') }}</p>
                </div>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- App Name -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="app_name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.application_name') }}</label>
                        <input type="text" name="app_name" id="app_name" value="{{ old('app_name', $setting->app_name) }}" class="block w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-sm transition-all font-semibold">
                        @error('app_name') <p class="mt-1.5 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Primary Color -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <label for="primary_color" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">{{ __('messages.primary_color') }}</label>
                        <div class="flex items-center space-x-4">
                            <div class="relative w-16 h-16 rounded-xl overflow-hidden shadow-sm border-2 border-white dark:border-gray-800">
                                <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', $setting->primary_color) }}" class="absolute -top-2 -left-2 w-20 h-20 cursor-pointer">
                            </div>
                            <div>
                                <span class="block text-lg font-mono font-bold text-gray-900 dark:text-white uppercase tracking-wider" id="color_hex">{{ old('primary_color', $setting->primary_color) }}</span>
                                <p class="mt-1 text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('messages.primary_color_desc') }}</p>
                            </div>
                        </div>
                        @error('primary_color') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <!-- Logo Upload -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">{{ __('messages.brand_logo') }}</label>
                        <div class="flex items-center space-x-4">
                            @if($setting->logo_path)
                                <div class="relative w-16 h-16 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center border border-gray-200 dark:border-gray-600 p-2 shrink-0">
                                    <img src="{{ Storage::url($setting->logo_path) }}" alt="Current Logo" class="max-h-full max-w-full object-contain">
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="logo" id="logo" accept="image/*" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer transition-colors">
                                <p class="mt-2 text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('messages.brand_logo_desc') }}</p>
                            </div>
                        </div>
                        @error('logo') <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                    <button type="submit" class="inline-flex justify-center items-center px-8 py-3 bg-gradient-to-r from-primary to-blue-600 hover:from-primary-dark hover:to-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        {{ __('messages.save_settings') }}
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>

    <script>
        document.getElementById('primary_color').addEventListener('input', function (e) {
            document.getElementById('color_hex').textContent = e.target.value.toUpperCase();
        });
    </script>
</x-app-layout>