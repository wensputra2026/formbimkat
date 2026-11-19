<?php

namespace App\Services;

use App\Models\Sekolah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BimkatSchoolSyncService
{
    /**
     * Sinkronisasi data master sekolah dari sumber BIMKAT.
     * Mengembalikan laporan jumlah berhasil, dilewati, duplikat, gagal.
     */
    public function sync(): array
    {
        $report = [
            'total_source' => 0,
            'berhasil' => 0,
            'dilewati' => 0,
            'duplikat' => 0,
            'gagal' => 0,
            'errors' => [],
        ];

        try {
            // Coba ambil dari koneksi bimkatlaravel12 jika tersedia, atau baca dari database lokal jika sudah disalin
            $sourceSchools = null;

            // Cek apakah database bimkatlaravel12 dapat diakses via query DB langsung
            try {
                $hasBimkatDb = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'bimkatlaravel12'");
                if (!empty($hasBimkatDb)) {
                    $sourceSchools = DB::table('bimkatlaravel12.sekolah as s')
                        ->leftJoin('bimkatlaravel12.kabupaten as k', 's.id_kabupaten', '=', 'k.id_kabupaten')
                        ->leftJoin('bimkatlaravel12.provinsi as p', 'k.id_provinsi', '=', 'p.id_provinsi')
                        ->select([
                            's.id_sekolah',
                            's.nama_sekolah',
                            's.npsn',
                            's.jenjang',
                            's.status_sekolah',
                            's.alamat_sekolah',
                            's.id_kabupaten',
                            's.id_kecamatan',
                            'k.nama_kabupaten as kabupaten',
                            'p.nama_provinsi as provinsi',
                        ])
                        ->get();
                }
            } catch (\Throwable $e) {
                Log::warning("Direct bimkat db query failed: " . $e->getMessage());
            }

            // Jika tidak ada koneksi langsung ke db bimkatlaravel12, gunakan tabel lokal sekolah yang sudah ada relasi kabupaten/provinsi
            if (!$sourceSchools || $sourceSchools->isEmpty()) {
                $sourceSchools = DB::table('sekolah as s')
                    ->leftJoin('kabupaten as k', 's.id_kabupaten', '=', 'k.id_kabupaten')
                    ->leftJoin('provinsi as p', 'k.id_provinsi', '=', 'p.id_provinsi')
                    ->select([
                        's.id_sekolah',
                        's.nama_sekolah',
                        's.npsn',
                        's.jenjang',
                        's.status_sekolah',
                        's.alamat_sekolah',
                        's.id_kabupaten',
                        's.id_kecamatan',
                        DB::raw('COALESCE(s.kabupaten, k.nama_kabupaten) as kabupaten'),
                        DB::raw('COALESCE(s.provinsi, p.nama_provinsi) as provinsi'),
                    ])
                    ->get();
            }

            $report['total_source'] = $sourceSchools->count();

            $seenNpsn = [];

            foreach ($sourceSchools as $source) {
                try {
                    $npsn = trim((string)($source->npsn ?? ''));
                    $idSekolah = (int)$source->id_sekolah;
                    $namaSekolah = trim((string)($source->nama_sekolah ?? ''));

                    if (empty($namaSekolah)) {
                        $report['dilewati']++;
                        continue;
                    }

                    if (!empty($npsn)) {
                        if (isset($seenNpsn[$npsn])) {
                            $report['duplikat']++;
                        } else {
                            $seenNpsn[$npsn] = true;
                        }
                    }

                    $existing = Sekolah::withTrashed()->where('id_sekolah', $idSekolah)->first();

                    if (!$existing && !empty($npsn)) {
                        $existing = Sekolah::withTrashed()->where('npsn', $npsn)->first();
                    }

                    $attributes = [
                        'nama_sekolah' => $namaSekolah,
                        'npsn' => $npsn ?: null,
                        'jenjang' => $source->jenjang ?: 'SD',
                        'status_sekolah' => $source->status_sekolah ?: 'Negeri',
                        'alamat_sekolah' => $source->alamat_sekolah ?: '-',
                        'kabupaten' => $source->kabupaten ?: 'Timor Tengah Selatan',
                        'provinsi' => $source->provinsi ?: 'Nusa Tenggara Timur',
                        'id_kabupaten' => $source->id_kabupaten ?? 1,
                        'id_kecamatan' => $source->id_kecamatan ?? 1,
                    ];

                    if ($existing) {
                        $existing->update($attributes);
                        if ($existing->trashed()) {
                            $existing->restore();
                        }
                    } else {
                        $attributes['id_sekolah'] = $idSekolah;
                        Sekolah::create($attributes);
                    }

                    $report['berhasil']++;
                } catch (\Throwable $err) {
                    $report['gagal']++;
                    $report['errors'][] = "Sekolah ID {$source->id_sekolah}: " . $err->getMessage();
                }
            }
        } catch (\Throwable $e) {
            $report['errors'][] = "General Sync Error: " . $e->getMessage();
        }

        return $report;
    }
}
