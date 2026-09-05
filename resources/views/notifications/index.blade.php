<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                Notifications
            </h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl shadow-md transition-all">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6">
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl shadow-xl rounded-3xl border border-white/40 dark:border-gray-700 overflow-hidden">
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($notifications as $notification)
                    <a href="{{ route('notifications.read', $notification->id) }}" class="block p-6 hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-colors {{ $notification->unread() ? 'bg-indigo-50/40 dark:bg-indigo-900/10' : '' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-base font-bold text-gray-900 dark:text-white">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </h4>
                                    @if($notification->unread())
                                        <span class="inline-block w-2 h-2 rounded-full bg-primary"></span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                            </div>
                            <span class="text-xs font-medium text-gray-400 whitespace-nowrap">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-700 dark:text-gray-300">No Notifications</h3>
                        <p class="text-xs text-gray-400 mt-1">You have no notification history at this time.</p>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
