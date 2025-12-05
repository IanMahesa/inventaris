<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::query()->with(['causer', 'subject']);

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhere('properties', 'like', "%{$q}%");
            });
        }

        if ($request->filled('user_id')) {
            $userId = (int) $request->get('user_id');
            if ($userId > 0) {
                $query->where('causer_id', $userId);
            }
        }

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->get('log_name'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $activities = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $users = User::query()->orderBy('name')->get(['id', 'name']);
        $logNames = Activity::query()
            ->select('log_name')
            ->whereNotNull('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name');

        $filters = [
            'q' => $request->get('q'),
            'user_id' => $request->get('user_id'),
            'log_name' => $request->get('log_name'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        return view('activitylogs.index', compact('activities', 'users', 'logNames', 'filters'));
    }
}
