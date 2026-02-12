<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Contracts\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        $logs = ActivityLog::query()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('activity-logs.index', compact('logs'));
    }
}

