<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendataan Guru</title>
    <style>
        @page { margin: 15mm 10mm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 8.5pt; color: #1e293b; }
        .header { text-align: center; border-bottom: 2px solid #0f2854; padding-bottom: 6px; margin-bottom: 12px; }
        .header h1 { font-size: 13pt; margin: 0; text-transform: uppercase; color: #0f2854; }
        .header h2 { font-size: 10.5pt; margin: 2px 0; }
        .header p { font-size: 8pt; margin: 0; color: #475569; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 0.5pt solid #94a3b8; padding: 4pt 5pt; text-align: left; }
        th { background-color: #0f2854; color: #ffffff; font-weight: bold; font-size: 8pt; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', monospace; font-size: 8pt; }
        .total-row { font-weight: bold; background-color: #e2e8f0 !important; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Kementerian Agama Republik Indonesia</h1>
        <h2>{{ $appName }}</h2>
        <p>{{ $instansi }}</p>
        <p style="margin-top: 3px; font-weight: bold;">Status: {{ $statusFilter }} | TPG: {{ $tpgFilter ?: 'Semua' }} | Tgl Unduh: {{ now('Asia/Makassar')->format('d/m/Y H:i:s') }} WITA</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 15pt;">No</th>
                <th>No. Registrasi</th>
                <th>Nama Lengkap</th>
                <th>NIP / NIK</th>
                <th>Pangkat/Gol</th>
                <th class="text-right">Gaji Pokok</th>
                <th>KGB Berikutnya</th>
                <th>Perkiraan Pensiun</th>
                <th>Sekolah</th>
                <th>NPSN</th>
                <th>TPG</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gurus as $index => $g)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $g->nomor_registrasi }}</td>
                    <td><strong>{{ $g->nama }}</strong></td>
                    <td class="font-mono">{{ $g->nip ?: ($g->nik ?: '-') }}</td>
                    <td>{{ $g->pangkatGolongan ? $g->pangkatGolongan->golongan : '-' }}</td>
                    <td class="text-right font-mono">{{ $g->formatted_gaji }}</td>
                    <td>{{ $g->kgb_formatted ?: '-' }}</td>
                    <td>{{ $g->pensiun_formatted ?: '-' }}</td>
                    <td>{{ $g->sekolah ? $g->sekolah->nama_sekolah : '-' }}</td>
                    <td class="font-mono">{{ $g->sekolah ? $g->sekolah->npsn : '-' }}</td>
                    <td>{{ $g->penerima_tpg ?: '-' }}</td>
                    <td class="text-center">{{ $g->status_verifikasi }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center" style="padding: 15pt;">Tidak ada data guru yang memenuhi filter.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="5" class="text-center">TOTAL ({{ count($gurus) }} GURU)</td>
                <td class="text-right font-mono">Rp {{ number_format($totalGaji, 2, ',', '.') }}</td>
                <td colspan="6"></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
