<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $actionUrl = $notification->data['action_url'] ?? null;

        if ($actionUrl) {
            // Parse the stored URL to get just the path
            // This prevents issues if the APP_URL in .env was missing the subfolder
            $parsedUrl = parse_url($actionUrl);
            $path = $parsedUrl['path'] ?? '';
            $path = trim($path, '/');

            // Rebuild with current web request's root URL
            $actionUrl = url($path);

            // Fix incorrect customers.show route in older notifications
            // If they point to /edit, but we want the ledger view now, we strip /edit
            if (strpos($actionUrl, '/customers/') !== false && strpos($actionUrl, '/edit') !== false) {
                $actionUrl = str_replace('/edit', '', $actionUrl);
            }
        } else {
            $actionUrl = route('dashboard');
        }

        return redirect()->to($actionUrl);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
