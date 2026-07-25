<header class="sticky top-0 z-50 flex items-center justify-between px-6 py-3 bg-white dark:bg-[#111827] border-t-2 border-primary border-b border-gray-200 dark:border-gray-800 transition-colors duration-200">
    <div class="flex items-center space-x-4">
        <!-- Sidebar Toggle -->
        <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white focus:outline-none lg:hidden">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <!-- Brand Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center">
            @php
                $settings = \App\Models\Setting::first();
            @endphp
            
            @if($settings && $settings->logo_path)
                <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-8">
            @else
                <div class="p-1.5 rounded bg-primary/20 text-primary">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <span class="mx-3 text-xl font-bold text-gray-900 dark:text-white hidden sm:block tracking-tight">{{ $settings->app_name ?? config('app.name', 'TeamTasker') }}</span>
            @endif
        </a>
    </div>

    <!-- Center Search Bar (Hidden on small screens) -->
    <div class="hidden md:flex flex-1 justify-center px-8">
        <form action="{{ route('search') }}" method="GET" class="relative w-full max-w-xl">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="q" value="{{ request('q') }}" class="block w-full p-2 pl-10 text-sm text-gray-900 dark:text-gray-200 bg-gray-100 dark:bg-[#1f2937] border-transparent rounded-lg focus:ring-primary focus:border-primary placeholder-gray-500" placeholder="Search customers, invoices...">
        </form>
    </div>
    
    <div class="flex items-center space-x-4">
        <!-- Theme Toggle -->
        <!-- Theme Toggle -->
        <button @click="darkMode = !darkMode" class="text-gray-500 dark:text-gray-400 focus:outline-none p-2 rounded-full bg-gray-100 dark:bg-[#1f2937] hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
            <!-- Moon icon for light mode -->
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <!-- Sun icon for dark mode -->
            <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        <!-- Notification Bell -->
        <div x-data="{ notifyOpen: false }" class="relative">
            <button @click="notifyOpen = !notifyOpen" class="text-gray-500 dark:text-gray-400 focus:outline-none p-2 rounded-full bg-gray-100 dark:bg-[#1f2937] hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-500 rounded-full">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </button>

            <div x-show="notifyOpen" @click="notifyOpen = false" class="fixed inset-0 z-10 w-full h-full" style="display: none;"></div>

            <div x-show="notifyOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="absolute right-0 z-10 w-80 mt-2 overflow-hidden bg-white dark:bg-gray-800 rounded-md shadow-xl border border-gray-100 dark:border-gray-700" style="display: none;">
                <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Notifications</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 dark:text-blue-400 hover:underline bg-transparent border-none p-0 cursor-pointer">Mark all as read</button>
                    </form>
                    @endif
                </div>
                <div class="max-h-64 overflow-y-auto">
                    @forelse(auth()->user()->unreadNotifications as $notification)
                        <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $notification->data['title'] ?? 'Notification' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </a>
                    @empty
                        <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">
                            No new notifications
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div x-data="{ dropdownOpen: false }" class="relative ml-2">
            <button @click="dropdownOpen = !dropdownOpen" class="relative block w-8 h-8 overflow-hidden rounded-full shadow focus:outline-none ring-2 ring-primary ring-offset-2 dark:ring-offset-gray-800">
                <img class="object-cover w-full h-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&color=ffffff&background={{ str_replace('#', '', \App\Models\Setting::first()->primary_color ?? '4f46e5') }}" alt="Your avatar">
            </button>

            <div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 z-10 w-full h-full" style="display: none;"></div>

            <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="absolute right-0 z-10 w-48 mt-2 overflow-hidden bg-white dark:bg-gray-800 rounded-md shadow-xl border border-gray-100 dark:border-gray-700" style="display: none;">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-primary hover:text-white transition-colors">Profile</a>
                <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-primary hover:text-white transition-colors">Settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-600 hover:text-white transition-colors">Logout</a>
                </form>
            </div>
        </div>
    </div>
</header>
