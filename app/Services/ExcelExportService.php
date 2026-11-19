<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\Setting;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExportService
{
    public const SHEET_CATEGORIES = [
        'Kemenag',
        'Kemenag 2026',
        'Dinas',
        'Dinas 2026',
        'PPPK',
        'PPPK 2026',
    ];

    /**
     * Export 6 Sheet Excel
     *
     * @param string|null $statusFilter 'Disetujui' (default), 'Menunggu Verifikasi', 'Ditolak', 'Semua'
     * @return StreamedResponse
     */
    public function exportSixSheets(?string $statusFilter = 'Disetujui'): StreamedResponse
    {
        $statusFilter = $statusFilter ?: 'Disetujui';
        $appName = Setting::get('app_name', 'Pendataan Guru Kementerian Agama');
        $instansi = Setting::get('instansi', 'Kantor Kementerian Agama Kabupaten Timor Tengah Selatan');

        $spreadsheet = new Spreadsheet();
        // Remove default sheet
        $spreadsheet->removeSheetByIndex(0);

        foreach (self::SHEET_CATEGORIES as $index => $category) {
            $sheet = $spreadsheet->createSheet($index);
            $sheet->setTitle($category);

            // Query data untuk kategori ini
            $query = Guru::with(['pangkatGolongan', 'sekolah', 'verifier'])
                ->where('penerima_tpg', $category);

            if ($statusFilter !== 'Semua') {
                $query->where('status_verifikasi', $statusFilter);
            }

            $gurus = $query->orderBy('nama')->get();

            // 1. Header Judul
            $sheet->setCellValue('A1', strtoupper($appName));
            $sheet->setCellValue('A2', strtoupper($instansi));
            $sheet->setCellValue('A3', "KATEGORI: " . strtoupper($category) . " | STATUS: " . strtoupper($statusFilter) . " | TANGGAL CETAK: " . now('Asia/Makassar')->format('d/m/Y H:i:s') . ' WITA');

            $sheet->mergeCells('A1:S1');
            $sheet->mergeCells('A2:S2');
            $sheet->mergeCells('A3:S3');

            $sheet->getStyle('A1:A3')->getFont()->setBold(true);
            $sheet->getStyle('A1')->getFont()->setSize(14);
            $sheet->getStyle('A2')->getFont()->setSize(12);
            $sheet->getStyle('A3')->getFont()->setSize(10)->getColor()->setARGB('FF4B5563');

            // 2. Header Kolom (Row 5)
            $headers = [
                'A5' => 'No.',
                'B5' => 'Nomor Registrasi',
                'C5' => 'Nama Lengkap',
                'D5' => 'NIP',
                'E5' => 'NIK',
                'F5' => 'Pangkat/Golongan',
                'G5' => 'Gaji Pokok Saat Ini',
                'H5' => 'Berkala Berikutnya – Bulan',
                'I5' => 'Berkala Berikutnya – Tahun',
                'J5' => 'Pensiun – Bulan',
                'K5' => 'Pensiun – Tahun',
                'L5' => 'Penerima TPG',
                'M5' => 'Nama Sekolah',
                'N5' => 'NPSN',
                'O5' => 'Kabupaten/Kota',
                'P5' => 'Provinsi',
                'Q5' => 'Status Verifikasi',
                'R5' => 'Tanggal Verifikasi',
                'S5' => 'Admin yang Memverifikasi',
            ];

            foreach ($headers as $cell => $text) {
                $sheet->setCellValue($cell, $text);
            }

            // Style Header Kolom
            $headerRange = 'A5:S5';
            $sheet->getStyle($headerRange)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E3A8A'], // Navy Blue
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);
            $sheet->getRowDimension(5)->setRowHeight(32);

            // 3. Isi Data
            $row = 6;
            if ($gurus->isEmpty()) {
                $sheet->setCellValue('A6', 'Tidak Ada Data');
                $sheet->mergeCells('A6:S6');
                $sheet->getStyle('A6:S6')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => ['italic' => true, 'color' => ['argb' => 'FF6B7280']],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFD1D5DB'],
                        ],
                    ],
                ]);
                $sheet->getRowDimension(6)->setRowHeight(24);
                $row = 7;
            } else {
                $no = 1;
                foreach ($gurus as $guru) {
                    $nipValue = $guru->nip ? "'" . $guru->nip : '-';
                    $nikValue = $guru->nik ? "'" . $guru->nik : '-';
                    $npsnValue = ($guru->sekolah && $guru->sekolah->npsn) ? "'" . $guru->sekolah->npsn : '-';

                    $sheet->setCellValue("A{$row}", $no);
                    $sheet->setCellValueExplicit("B{$row}", (string)$guru->nomor_registrasi, DataType::TYPE_STRING);
                    $sheet->setCellValue("C{$row}", $guru->nama);
                    $sheet->setCellValueExplicit("D{$row}", $nipValue, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit("E{$row}", $nikValue, DataType::TYPE_STRING);
                    $sheet->getStyle("D{$row}:E{$row}")->getNumberFormat()->setFormatCode('@');
                    $sheet->setCellValue("F{$row}", $guru->pangkatGolongan ? $guru->pangkatGolongan->label : '-');
                    
                    // Gaji Pokok
                    $sheet->setCellValue("G{$row}", (float)$guru->gaji_pokok);
                    $sheet->getStyle("G{$row}")->getNumberFormat()->setFormatCode('"Rp "#,##0.00');

                    // KGB & Pensiun
                    $sheet->setCellValue("H{$row}", $guru->kgb_bulan ? (Guru::BULAN[$guru->kgb_bulan] ?? $guru->kgb_bulan) : '-');
                    $sheet->setCellValue("I{$row}", $guru->kgb_tahun ?: '-');
                    $sheet->setCellValue("J{$row}", $guru->pensiun_bulan ? (Guru::BULAN[$guru->pensiun_bulan] ?? $guru->pensiun_bulan) : '-');
                    $sheet->setCellValue("K{$row}", $guru->pensiun_tahun ?: '-');

                    // TPG & Sekolah
                    $sheet->setCellValue("L{$row}", $guru->penerima_tpg ?: '-');
                    $sheet->setCellValue("M{$row}", $guru->sekolah ? $guru->sekolah->nama_sekolah : '-');
                    $sheet->setCellValueExplicit("N{$row}", $npsnValue, DataType::TYPE_STRING);
                    $sheet->getStyle("N{$row}")->getNumberFormat()->setFormatCode('@');
                    $sheet->setCellValue("O{$row}", $guru->sekolah ? $guru->sekolah->kabupaten : '-');
                    $sheet->setCellValue("P{$row}", $guru->sekolah ? $guru->sekolah->provinsi : '-');

                    // Verifikasi
                    $sheet->setCellValue("Q{$row}", $guru->status_verifikasi);
                    $sheet->setCellValue("R{$row}", $guru->diverifikasi_pada ? $guru->diverifikasi_pada->copy()->timezone('Asia/Makassar')->format('d/m/Y H:i:s') . ' WITA' : '-');
                    $sheet->setCellValue("S{$row}", $guru->verifier ? $guru->verifier->name : '-');

                    // Style row
                    $sheet->getStyle("A{$row}:S{$row}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FFD1D5DB'],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("D{$row}:E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("H{$row}:L{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("N{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("Q{$row}:R{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $no++;
                    $row++;
                }

                // Baris Total
                $totalRow = $row;
                $sheet->setCellValue("A{$totalRow}", 'TOTAL GURU');
                $sheet->mergeCells("A{$totalRow}:F{$totalRow}");
                $sheet->setCellValue("G{$totalRow}", "=SUM(G6:G" . ($totalRow - 1) . ")");
                $sheet->getStyle("G{$totalRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0.00');
                $sheet->setCellValue("H{$totalRow}", count($gurus) . " Orang");
                $sheet->mergeCells("H{$totalRow}:S{$totalRow}");

                $sheet->getStyle("A{$totalRow}:S{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF3F4F6'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF9CA3AF'],
                        ],
                    ],
                ]);
                $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            // Freeze panes di bawah header
            $sheet->freezePane('A6');

            // AutoFilter
            $sheet->setAutoFilter('A5:S' . max(5, $row - 1));

            // Auto column width
            foreach (range('A', 'S') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        // Set active sheet ke sheet pertama
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Pendataan_Guru_Kemenag_' . date('Ymd_His') . '.xlsx';

        return new StreamedResponse(
            function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control' => 'max-age=0',
            ]
        );
    }
}
