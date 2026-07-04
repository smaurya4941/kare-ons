<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Full notification centre page.
     */
    public function index(Request $request): View
    {
        $filter = $request->query('filter');

        $query = AdminNotification::query()->latest();

        if ($filter === 'unread') {
            $query->unread();
        } elseif ($filter === 'read') {
            $query->read();
        } elseif (in_array($filter, ['order', 'low_stock', 'review', 'message'], true)) {
            $query->ofType($filter);
        }

        $notifications = $query->paginate(20)->withQueryString();
        $unreadCount   = AdminNotification::unreadCount();

        return view('admin.notifications.index', compact('notifications', 'unreadCount', 'filter'));
    }

    /**
     * Lightweight JSON feed for the header bell (polled by Alpine).
     */
    public function feed(): JsonResponse
    {
        $notifications = AdminNotification::recent(8)->map(fn ($n) => [
            'id'         => $n->id,
            'type'       => $n->type,
            'title'      => $n->title,
            'message'    => $n->message,
            'url'        => $n->url,
            'icon'       => $n->icon ?? 'notifications',
            'color'      => $n->color ?? 'gray',
            'is_read'    => $n->is_read,
            'created_at' => $n->created_at->diffForHumans(),
        ]);

        return response()->json([
            'unread_count'  => AdminNotification::unreadCount(),
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a single notification read and forward to its target, if any.
     */
    public function read(AdminNotification $notification): RedirectResponse
    {
        $notification->markAsRead();

        if ($notification->url) {
            return redirect()->to($notification->url);
        }

        return redirect()->route('admin.notifications.index');
    }

    public function markAllRead(): RedirectResponse
    {
        AdminNotification::unread()->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(AdminNotification $notification): RedirectResponse
    {
        $notification->delete();

        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Clear notifications that have already been read.
     */
    public function clearRead(): RedirectResponse
    {
        AdminNotification::read()->delete();

        return back()->with('success', 'Read notifications cleared.');
    }
}
