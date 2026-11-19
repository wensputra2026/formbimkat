<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\PangkatGolongan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama (Admin Tidak Perlu Verifikasi)
        $totalGuru = Guru::count();
        $totalTpg = Guru::whereNotNull('penerima_tpg')->where('penerima_tpg', '!=', '')->count();
        $totalSekolah = Sekolah::count();
        $totalPangkat = PangkatGolongan::count();

        // 2. Statistik 6 Kategori TPG
        $tpgCategories = Guru::TPG_CHOICES;
        $tpgCounts = [];
        foreach ($tpgCategories as $tpg) {
            $tpgCounts[$tpg] = Guru::where('penerima_tpg', $tpg)->count();
        }

        // 3. Ringkasan per Pangkat / Golongan
        $byPangkat = DB::table('gurus as g')
            ->join('pangkat_golongans as p', 'g.pangkat_golongan_id', '=', 'p.id')
            ->whereNull('g.deleted_at')
            ->select('p.golongan', 'p.pangkat', DB::raw('count(*) as total'))
            ->groupBy('p.golongan', 'p.pangkat')
            ->orderByDesc('total')
            ->get();

        // 4. Ringkasan per Kabupaten
        $byKabupaten = DB::table('gurus as g')
            ->join('sekolah as s', 'g.sekolah_id', '=', 's.id_sekolah')
            ->whereNull('g.deleted_at')
            ->select(DB::raw('COALESCE(s.kabupaten, "Tidak Diketahui") as kabupaten'), DB::raw('count(*) as total'))
            ->groupBy('kabupaten')
            ->orderByDesc('total')
            ->get();

        // 5. Top 5 Sekolah dengan Guru Terbanyak
        $topSchools = DB::table('gurus as g')
            ->join('sekolah as s', 'g.sekolah_id', '=', 's.id_sekolah')
            ->whereNull('g.deleted_at')
            ->select('s.nama_sekolah', 's.npsn', DB::raw('count(*) as total'))
            ->groupBy('s.nama_sekolah', 's.npsn')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // 6. Data Guru Terbaru yang Masuk dari Formulir Publik
        $recentGurus = Guru::with(['sekolah', 'pangkatGolongan'])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalGuru',
            'totalTpg',
            'totalSekolah',
            'totalPangkat',
            'tpgCategories',
            'tpgCounts',
            'byPangkat',
            'byKabupaten',
            'topSchools',
            'recentGurus'
        ));
    }
}
