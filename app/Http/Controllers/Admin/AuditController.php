<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\LoginHistory;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function loginHistories(Request $request)
    {
        $query = LoginHistory::with('user');

        if ($search = trim((string)$request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('browser', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($date = $request->query('date')) {
            try {
                $localDate = Carbon::createFromFormat('!d/m/Y', $date, 'Asia/Makassar');
                $start = $localDate->copy()->startOfDay()->utc();
                $end = $localDate->copy()->endOfDay()->utc();
                $query->whereBetween('login_at', [$start, $end]);
            } catch (\Throwable) {
                // Abaikan tanggal filter yang tidak sesuai format dd/mm/yyyy.
            }
        }

        $totalAttempts = (clone $query)->count();
        $successfulAttempts = (clone $query)->where('status', 'success')->count();
        $failedAttempts = (clone $query)->where('status', 'failed')->count();
        $histories = $query->latest('login_at')->paginate(10)->withQueryString();
        $guruSubmissions = Guru::withTrashed()
            ->with(['sekolah', 'pangkatGolongan'])
            ->latest('created_at')
            ->paginate(10, ['*'], 'guru_page')
            ->withQueryString();
        $submissionLogs = ActivityLog::query()
            ->where('action', 'submit_form_guru')
            ->where('subject_type', Guru::class)
            ->whereIn('subject_id', $guruSubmissions->getCollection()->pluck('id'))
            ->get()
            ->keyBy('subject_id');
        $totalGuruSubmissions = Guru::withTrashed()->count();

        return view('admin.audit.login_histories', compact(
            'histories',
            'totalAttempts',
            'successfulAttempts',
            'failedAttempts',
            'guruSubmissions',
            'submissionLogs',
            'totalGuruSubmissions',
        ));
    }

    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($search = trim((string)$request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($action = $request->query('action')) {
            $query->where('action', $action);
        }

        if ($date = $request->query('date')) {
            try {
                $localDate = Carbon::createFromFormat('!d/m/Y', $date, 'Asia/Makassar');
                $start = $localDate->copy()->startOfDay()->utc();
                $end = $localDate->copy()->endOfDay()->utc();
                $query->whereBetween('created_at', [$start, $end]);
            } catch (\Throwable) {
                // Abaikan tanggal filter yang tidak sesuai format dd/mm/yyyy.
            }
        }

        $logs = $query->latest('created_at')->paginate(10)->withQueryString();
        $actions = ActivityLog::distinct()->pluck('action');

        return view('admin.audit.activity_logs', compact('logs', 'actions'));
    }
}
