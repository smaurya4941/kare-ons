<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityLog::query()->with('causer')->latest();

        if ($event = $request->query('event')) {
            $query->where('event', $event);
        }

        if ($type = $request->query('type')) {
            // Match on the short class name (e.g. "Product").
            $query->where('subject_type', 'App\\Models\\' . $type);
        }

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('causer_name', 'like', "%{$search}%");
            });
        }

        if ($from = $request->query('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->query('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $logs = $query->paginate(25)->withQueryString();

        // Distinct subject types present, for the filter dropdown.
        $subjectTypes = ActivityLog::query()
            ->whereNotNull('subject_type')
            ->distinct()
            ->pluck('subject_type')
            ->map(fn ($t) => class_basename($t))
            ->unique()
            ->sort()
            ->values();

        return view('admin.activity.index', compact('logs', 'subjectTypes'));
    }

    public function show(ActivityLog $activity): View
    {
        $activity->load('causer', 'subject');

        return view('admin.activity.show', compact('activity'));
    }

    /**
     * Prune log entries older than the given number of days (default 90).
     */
    public function clear(Request $request): RedirectResponse
    {
        $days = (int) $request->input('days', 90);
        $days = max(0, $days);

        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return back()->with('success', "Cleared {$deleted} activity log " . str('entry')->plural($deleted) . " older than {$days} days.");
    }
}
