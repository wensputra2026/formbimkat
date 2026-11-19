@extends('layouts.admin')

@section('title', 'Detail Guru — ' . $guru->nama)
@section('page_title', 'Detail Data Guru')

@section('content')
<div style="width: 100%; display: flex; flex-direction: column; gap: 20px;">

    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('admin.guru.index') }}" class="pd-button pd-button-secondary">
            <span>← Kembali ke Data Guru</span>
        </a>

        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.guru.edit', $guru->id) }}" class="pd-button pd-button-primary">
                <span>✎ Perbaiki Data Guru</span>
            </a>
            @if(!$guru->trashed())
                <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST" data-swal-title="Arsipkan data guru?" data-swal-confirm="Data guru akan dipindahkan ke arsip." data-swal-confirm-button="Ya, arsipkan" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="pd-button pd-button-danger">
                        <span>Hapus / Arsipkan</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Status Data -->
    <div class="pd-panel" style="padding: 16px 22px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span class="pd-badge is-success" style="font-size: 11px; padding: 5px 12px;">
                <i></i> DATA TERCATAT RESMI
            </span>
            <span style="color: var(--pd-muted); font-size: 12px;">
                Diterbitkan pada {{ $guru->created_at->format('d/m/Y H:i') }} WITA
            </span>
        </div>
        <span style="font-family: monospace; font-size: 13px; font-weight: 800; color: #ea580c;">
            {{ $guru->nomor_registrasi }}
        </span>
    </div>

    <!-- Panel Rincian Informasi Guru -->
    <div class="pd-panel" style="padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--pd-border); padding-bottom: 14px; margin-bottom: 20px;">
            <h2 style="margin: 0; font-size: 14px; font-weight: 800; color: #0f172a;">Informasi Lengkap Guru</h2>
            <strong style="font-family: monospace; color: #ea580c; font-size: 13px; font-weight: 800;">{{ $guru->nomor_registrasi }}</strong>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; font-size: 12px;">
            <div>
                <span class="pd-label" style="font-size: 10px; color: #475569;">Nama Lengkap:</span>
                <strong style="font-size: 15px; color: #0f172a; font-weight: 800;">{{ $guru->nama }}</strong>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px; color: #475569;">Kategori Penerima TPG:</span>
                <span class="pd-badge is-info"><i></i> {{ $guru->penerima_tpg ?: 'Belum Ditentukan' }}</span>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px; color: #475569;">NIP (18 Digit):</span>
                <span style="font-family: monospace; font-size: 13px; color: #0f172a; font-weight: 700;">{{ $guru->nip ?: '-' }}</span>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px; color: #475569;">NIK (16 Digit):</span>
                <span style="font-family: monospace; font-size: 13px; color: #0f172a; font-weight: 700;">{{ $guru->nik ?: '-' }}</span>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px; color: #475569;">Pangkat / Golongan:</span>
                <strong style="color: #0f172a; font-size: 13px; font-weight: 700;">{{ $guru->pangkatGolongan ? $guru->pangkatGolongan->label : '-' }}</strong>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px; color: #475569;">Gaji Pokok:</span>
                <strong style="font-size: 15px; color: #047857; font-weight: 800;">{{ $guru->formatted_gaji }}</strong>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px; color: #475569;">Berkala Berikutnya (KGB):</span>
                <strong style="color: #0f172a; font-size: 13px; font-weight: 700;">{{ $guru->kgb_formatted }}</strong>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px; color: #475569;">Perkiraan Pensiun:</span>
                <strong style="color: #0f172a; font-size: 13px; font-weight: 700;">{{ $guru->pensiun_formatted }}</strong>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px; color: #475569;">Waktu Pendaftaran:</span>
                <span style="color: #475569; font-weight: 600;">{{ $guru->created_at->format('d/m/Y H:i:s') }} WITA</span>
            </div>

            <!-- Satuan Pendidikan -->
            <div style="grid-column: 1 / -1; margin-top: 10px; padding: 16px; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0;">
                <span class="pd-eyebrow" style="margin-bottom: 8px;">SATUAN PENDIDIKAN TEMPAT TUGAS</span>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                    <div>
                        <span class="pd-label" style="font-size: 9px; color: #475569;">Nama Sekolah</span>
                        <strong style="font-size: 14px; color: #0f172a; font-weight: 800;">{{ $guru->sekolah ? $guru->sekolah->nama_sekolah : '-' }}</strong>
                    </div>
                    <div>
                        <span class="pd-label" style="font-size: 9px; color: #475569;">NPSN</span>
                        <strong style="font-family: monospace; color: #ea580c; font-size: 13px; font-weight: 700;">{{ $guru->sekolah ? $guru->sekolah->npsn : '-' }}</strong>
                    </div>
                    <div>
                        <span class="pd-label" style="font-size: 9px; color: #475569;">Wilayah</span>
                        <span style="color: #334155; font-weight: 600;">{{ $guru->sekolah ? $guru->sekolah->kabupaten : '-' }} ({{ $guru->sekolah ? $guru->sekolah->provinsi : '-' }})</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Audit / Perubahan Guru Ini -->
    <div class="pd-panel" style="padding: 24px;">
        <div class="pd-panel-heading" style="margin-bottom: 14px;">
            <div>
                <h2>Riwayat Log Audit & Perubahan Guru Ini</h2>
                <p>Mencatat setiap tindakan admin terhadap data ini (nilai sebelum & sesudah)</p>
            </div>
            <span class="pd-badge is-info">{{ count($auditLogs) }} Catatan</span>
        </div>

        <div class="pd-activity-list">
            @forelse($auditLogs as $log)
                <div class="pd-activity-item">
                    <span class="pd-activity-icon">✎</span>
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: baseline;">
                            <strong style="font-size: 12px; color: #0f172a; font-weight: 700;">{{ $log->description }}</strong>
                            <small style="color: #64748b; font-weight: 500;">{{ $log->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        
                        @if($log->properties && isset($log->properties['sebelum']))
                            <div style="margin-top: 8px; padding: 10px 12px; border-radius: 8px; background: #f8fafc; border: 1px solid #e2e8f0; font-family: monospace; font-size: 10.5px; display: flex; flex-direction: column; gap: 4px;">
                                @foreach($log->properties['sesudah'] as $k => $v)
                                    @if(($log->properties['sebelum'][$k] ?? '') != $v)
                                        <div style="color: #334155;">
                                            <span style="color: #64748b;">{{ $k }}:</span>
                                            <span style="text-decoration: line-through; color: #dc2626;">{{ $log->properties['sebelum'][$k] ?? '-' }}</span>
                                            <span>→</span>
                                            <strong style="color: #16a34a;">{{ $v ?: '-' }}</strong>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <small style="color: #64748b; font-size: 9.5px; margin-top: 4px; display: block;">IP: {{ $log->ip_address }}</small>
                    </div>
                </div>
            @empty
                <p style="color: #64748b; font-size: 11px; text-align: center; padding: 16px 0; font-style: italic;">
                    Belum ada riwayat aktivitas terkait data guru ini.
                </p>
            @endforelse
        </div>
    </div>
</div>
@endsection
