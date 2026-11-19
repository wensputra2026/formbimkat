<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Guru;
use App\Models\PangkatGolongan;
use App\Models\Sekolah;
use App\Models\Setting;
use App\Services\ExcelExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'Disetujui');
        $tpgFilter = $request->query('tpg');
        $sekolahFilter = $request->query('sekolah_id');
        $kabupatenFilter = $request->query('kabupaten');
        $pangkatFilter = $request->query('pangkat_id');

        $query = Guru::with(['sekolah', 'pangkatGolongan']);

        if ($statusFilter && $statusFilter !== 'Semua') {
            $query->where('status_verifikasi', $statusFilter);
        }
        if ($tpgFilter) {
            $query->where('penerima_tpg', $tpgFilter);
        }
        if ($sekolahFilter) {
            $query->where('sekolah_id', $sekolahFilter);
        }
        if ($kabupatenFilter) {
            $query->whereHas('sekolah', fn($q) => $q->where('kabupaten', $kabupatenFilter));
        }
        if ($pangkatFilter) {
            $query->where('pangkat_golongan_id', $pangkatFilter);
        }

        $gurus = $query->orderBy('nama')->paginate(10)->withQueryString();
        $totalRecords = $query->count();
        $totalGaji = (clone $query)->sum('gaji_pokok');

        $tpgChoices = Guru::getTpgChoices();
        $pangkats = PangkatGolongan::where('is_active', true)->orderBy('urutan')->get();
        $kabupatens = Sekolah::distinct()->whereNotNull('kabupaten')->pluck('kabupaten');

        return view('admin.report.index', compact(
            'gurus',
            'totalRecords',
            'totalGaji',
            'statusFilter',
            'tpgFilter',
            'sekolahFilter',
            'kabupatenFilter',
            'pangkatFilter',
            'tpgChoices',
            'pangkats',
            'kabupatens'
        ));
    }

    /**
     * EXPORT EXCEL – 6 SHEET
     */
    public function exportExcel(Request $request, ExcelExportService $exportService)
    {
        $statusFilter = $request->query('status', 'Disetujui');

        ActivityLog::log(
            'export_excel',
            "Admin " . Auth::user()->name . " melakukan EXPORT EXCEL 6 SHEET dengan filter status: {$statusFilter}"
        );

        return $exportService->exportSixSheets($statusFilter);
    }

    /**
     * Cetak / Export PDF
     */
    public function exportPdf(Request $request)
    {
        $statusFilter = $request->query('status', 'Disetujui');
        $tpgFilter = $request->query('tpg');
        $kabupatenFilter = $request->query('kabupaten');

        $query = Guru::with(['sekolah', 'pangkatGolongan']);

        if ($statusFilter && $statusFilter !== 'Semua') {
            $query->where('status_verifikasi', $statusFilter);
        }
        if ($tpgFilter) {
            $query->where('penerima_tpg', $tpgFilter);
        }
        if ($kabupatenFilter) {
            $query->whereHas('sekolah', fn($q) => $q->where('kabupaten', $kabupatenFilter));
        }

        $gurus = $query->orderBy('nama')->get();
        $appName = Setting::get('app_name', 'Pendataan Guru Kementerian Agama');
        $instansi = Setting::get('instansi', 'Kantor Kementerian Agama Kabupaten Timor Tengah Selatan');
        $totalGaji = $gurus->sum('gaji_pokok');

        ActivityLog::log(
            'export_pdf',
            "Admin " . Auth::user()->name . " mencetak/mengekspor PDF Laporan Guru. Total Data: " . count($gurus)
        );

        if ($request->query('mode') === 'stream') {
            $pdf = Pdf::loadView('admin.report.pdf', compact('gurus', 'appName', 'instansi', 'totalGaji', 'statusFilter', 'tpgFilter'))
                ->setPaper('a4', 'landscape');
            return $pdf->stream('Laporan_Pendataan_Guru_' . date('Ymd_His') . '.pdf');
        }

        // Default printable web view
        return view('admin.report.print', compact('gurus', 'appName', 'instansi', 'totalGaji', 'statusFilter', 'tpgFilter'));
    }
}
