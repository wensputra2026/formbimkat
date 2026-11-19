<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Pendataan Guru - {{ $appName }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1e293b; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #0f2854; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { font-size: 15px; margin: 0; text-transform: uppercase; }
        .header h2 { font-size: 13px; margin: 3px 0; color: #0f2854; }
        .header p { font-size: 10px; margin: 0; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background-color: #0f2854; color: #ffffff; font-weight: bold; font-size: 10px; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; }
        .total-row { font-weight: bold; background-color: #e2e8f0 !important; }
        .actions { margin-bottom: 15px; }
        .btn { padding: 6px 12px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 12px; display: inline-block; border: none; cursor: pointer; }
        @media print {
            .actions { display: none; }
            body { margin: 0; font-size: 10px; }
            th { background-color: #eee !important; color: #000 !important; }
        }
    </style>
</head>
<body>

    <div class="actions">
        <button onclick="window.print()" class="btn">🖨 Cetak Dokumen</button>
        <span style="font-size: 11px; color: #64748b; margin-left: 10px;">Gunakan orientasi landscape saat mencetak.</span>
    </div>

    <div class="header">
        <h1>Kementerian Agama Republik Indonesia</h1>
        <h2>{{ $appName }}</h2>
        <p>{{ $instansi }}</p>
        <p style="margin-top: 5px; font-weight: bold;">Status: {{ $statusFilter }} | Kategori TPG: {{ $tpgFilter ?: 'Semua Kategori' }} | Tanggal Cetak: {{ now('Asia/Makassar')->format('d/m/Y H:i:s') }} WITA</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">No</th>
                <th>No. Registrasi</th>
                <th>Nama Lengkap</th>
                <th>NIP / NIK</th>
                <th>Golongan</th>
                <th class="text-right">Gaji Pokok</th>
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
                    <td>{{ $g->sekolah ? $g->sekolah->nama_sekolah : '-' }}</td>
                    <td class="font-mono">{{ $g->sekolah ? $g->sekolah->npsn : '-' }}</td>
                    <td>{{ $g->penerima_tpg ?: '-' }}</td>
                    <td class="text-center">{{ $g->status_verifikasi }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="padding: 20px;">Tidak ada data yang dicetak.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="6" class="text-center">TOTAL KESELURUHAN ({{ count($gurus) }} GURU)</td>
                <td class="text-right font-mono">Rp {{ number_format($totalGaji, 2, ',', '.') }}</td>
                <td colspan="4"></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
