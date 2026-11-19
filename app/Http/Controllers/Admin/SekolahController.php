<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Sekolah;
use App\Services\BimkatSchoolSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
        $query = Sekolah::query();

        if ($search = trim((string)$request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_sekolah', 'like', "%{$search}%")
                  ->orWhere('npsn', 'like', "%{$search}%")
                  ->orWhere('alamat_sekolah', 'like', "%{$search}%");
            });
        }

        if ($jenjang = $request->query('jenjang')) {
            $query->where('jenjang', $jenjang);
        }

        if ($status = $request->query('status_sekolah')) {
            $query->where('status_sekolah', $status);
        }

        if ($kabupaten = $request->query('kabupaten')) {
            $query->where('kabupaten', $kabupaten);
        }

        $sekolahs = $query->orderBy('nama_sekolah')->paginate(10)->withQueryString();

        $totalSekolah = Sekolah::count();
        $jenjangList = Sekolah::distinct()->whereNotNull('jenjang')->pluck('jenjang');
        $kabupatenList = Sekolah::distinct()->whereNotNull('kabupaten')->pluck('kabupaten');

        return view('admin.sekolah.index', compact('sekolahs', 'totalSekolah', 'jenjangList', 'kabupatenList'));
    }

    public function sync(BimkatSchoolSyncService $syncService)
    {
        $report = $syncService->sync();

        ActivityLog::log(
            'sync_sekolah',
            "Admin " . Auth::user()->name . " melakukan sinkronisasi Master Sekolah dari BIMKAT. Berhasil: {$report['berhasil']}, Dilewati: {$report['dilewati']}, Duplikat: {$report['duplikat']}, Gagal: {$report['gagal']}"
        );

        $msg = "Sinkronisasi Master Sekolah selesai. Total Sumber: {$report['total_source']}, Berhasil: {$report['berhasil']}, Dilewati: {$report['dilewati']}, Duplikat: {$report['duplikat']}, Gagal: {$report['gagal']}.";

        if ($report['gagal'] > 0) {
            return back()->with('warning', $msg);
        }

        return back()->with('success', $msg);
    }
}
