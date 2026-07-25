<aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
    class="fixed inset-y-0 left-0 z-[60] w-64 overflow-y-auto transition duration-300 transform bg-white dark:bg-gray-900 lg:translate-x-0 lg:static lg:inset-0 shadow-lg border-r border-gray-200 dark:border-gray-800">
    <div class="mt-4">
        <!-- Close button for mobile -->
        <div class="flex items-center justify-between px-4 lg:hidden mb-4">
            <span class="text-xl font-bold text-gray-900 dark:text-white">Menu</span>
            <button @click="sidebarOpen = false"
                class="text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <style>
            /* Active sidebar border logic with primary color */
            .border-active-sidebar {
                border-left-color: var(--color-primary);
                background-color: rgba(255, 255, 255, 0.05);
            }
        </style>
        <ul class="space-y-1 px-4">
            <li>
                <a href="{{ route('dashboard') }}"
                    class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-white border-l-4 rounded-r-md {{ request()->routeIs('dashboard') ? 'border-active-sidebar text-primary dark:text-white bg-gray-50 dark:bg-gray-800' : 'border-transparent' }} pr-6 transition-colors">
                    <span class="inline-flex justify-center items-center ml-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </span>
                    <span class="ml-2 text-sm tracking-wide truncate">Dashboard</span>
                </a>
            </li>
            @can('manage_newspapers')
                <li class="mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider ml-4 mb-2">Master Data</li>
                <li>
                    <a href="{{ route('newspapers.index') }}"
                        class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-white border-l-4 rounded-r-md {{ request()->routeIs('newspapers.*') ? 'border-active-sidebar text-primary dark:text-white bg-gray-50 dark:bg-gray-800' : 'border-transparent' }} pr-6 transition-colors">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                </path>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Newspapers</span>
                    </a>
                </li>
            @endcan
            @can('manage_customers')
                <li>
                    <a href="{{ route('customers.index') }}"
                        class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-white border-l-4 rounded-r-md {{ request()->routeIs('customers.*') ? 'border-active-sidebar text-primary dark:text-white bg-gray-50 dark:bg-gray-800' : 'border-transparent' }} pr-6 transition-colors">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Customers</span>
                    </a>
                </li>
            @endcan

            @can('manage_collections')
                <li class="mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider ml-4 mb-2">Finance</li>
                <li>
                    <a href="{{ route('daily-collections.index') }}"
                        class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-white border-l-4 rounded-r-md {{ request()->routeIs('daily-collections.*') ? 'border-active-sidebar text-primary dark:text-white bg-gray-50 dark:bg-gray-800' : 'border-transparent' }} pr-6 transition-colors">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Daily Collections</span>
                    </a>
                </li>
            @endcan
            @can('manage_payments')
                <li>
                    <a href="{{ route('payments.index') }}"
                        class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-white border-l-4 rounded-r-md {{ request()->routeIs('payments.*') ? 'border-active-sidebar text-primary dark:text-white bg-gray-50 dark:bg-gray-800' : 'border-transparent' }} pr-6 transition-colors">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Payments History</span>
                    </a>
                </li>
            @endcan
            @can('manage_purchases')
                <li>
                    <a href="{{ route('purchases.index') }}"
                        class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-white border-l-4 rounded-r-md {{ request()->routeIs('purchases.*') ? 'border-active-sidebar text-primary dark:text-white bg-gray-50 dark:bg-gray-800' : 'border-transparent' }} pr-6 transition-colors">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Vendor Purchases</span>
                    </a>
                </li>
            @endcan

            @can('view_reports')
                <li class="mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider ml-4 mb-2">Reports</li>
                <li>
                    <a href="{{ route('reports.index') }}"
                        class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-white border-l-4 rounded-r-md {{ request()->routeIs('reports.*') ? 'border-active-sidebar text-primary dark:text-white bg-gray-50 dark:bg-gray-800' : 'border-transparent' }} pr-6 transition-colors">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Reports</span>
                    </a>
                </li>
            @endcan

            @can('manage_settings')
                <li class="mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider ml-4 mb-2">System</li>
                <li>
                    <a href="{{ route('settings.index') }}"
                        class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-white border-l-4 rounded-r-md {{ request()->routeIs('settings.*') ? 'border-active-sidebar text-primary dark:text-white bg-gray-50 dark:bg-gray-800' : 'border-transparent' }} pr-6 transition-colors">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Settings</span>
                    </a>
                </li>
            @endcan
            @if(is_null(auth()->user()->owner_id))
                <li>
                    <a href="{{ route('users.index') }}"
                        class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-white border-l-4 rounded-r-md {{ request()->routeIs('users.*') ? 'border-active-sidebar text-primary dark:text-white bg-gray-50 dark:bg-gray-800' : 'border-transparent' }} pr-6 transition-colors">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('roles.index') }}"
                        class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-white border-l-4 rounded-r-md {{ request()->routeIs('roles.*') ? 'border-active-sidebar text-primary dark:text-white bg-gray-50 dark:bg-gray-800' : 'border-transparent' }} pr-6 transition-colors">
                        <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm tracking-wide truncate">Roles</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>