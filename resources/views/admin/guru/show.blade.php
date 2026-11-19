@extends('layouts.admin')

@section('title', 'Detail Guru — ' . $guru->nama)
@section('page_title', 'Detail Data Guru')

@section('content')
<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">

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
        <span style="font-family: monospace; font-size: 13px; font-weight: 800; color: var(--pd-accent);">
            {{ $guru->nomor_registrasi }}
        </span>
    </div>

    <!-- Panel Rincian Informasi Guru -->
    <div class="pd-panel" style="padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--pd-border); padding-bottom: 14px; margin-bottom: 20px;">
            <h2 style="margin: 0; font-size: 14px; font-weight: 800; color: #f4f5f7;">Informasi Lengkap Guru</h2>
            <strong style="font-family: monospace; color: #ffbd78; font-size: 13px;">{{ $guru->nomor_registrasi }}</strong>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; font-size: 12px;">
            <div>
                <span class="pd-label" style="font-size: 10px;">Nama Lengkap:</span>
                <strong style="font-size: 14px; color: #f4f5f7;">{{ $guru->nama }}</strong>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px;">Kategori Penerima TPG:</span>
                <span class="pd-badge is-info"><i></i> {{ $guru->penerima_tpg ?: 'Belum Ditentukan' }}</span>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px;">NIP (18 Digit):</span>
                <span style="font-family: monospace; font-size: 13px; color: #cbd5e1;">{{ $guru->nip ?: '-' }}</span>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px;">NIK (16 Digit):</span>
                <span style="font-family: monospace; font-size: 13px; color: #cbd5e1;">{{ $guru->nik ?: '-' }}</span>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px;">Pangkat / Golongan:</span>
                <strong style="color: #f4f5f7;">{{ $guru->pangkatGolongan ? $guru->pangkatGolongan->label : '-' }}</strong>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px;">Gaji Pokok:</span>
                <strong style="font-size: 14px; color: #6bd4ae;">{{ $guru->formatted_gaji }}</strong>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px;">Berkala Berikutnya (KGB):</span>
                <strong style="color: #f4f5f7;">{{ $guru->kgb_formatted }}</strong>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px;">Perkiraan Pensiun:</span>
                <strong style="color: #f4f5f7;">{{ $guru->pensiun_formatted }}</strong>
            </div>

            <div>
                <span class="pd-label" style="font-size: 10px;">Waktu Pendaftaran:</span>
                <span style="color: var(--pd-muted);">{{ $guru->created_at->format('d/m/Y H:i:s') }} WITA</span>
            </div>

            <!-- Satuan Pendidikan -->
            <div style="grid-column: 1 / -1; margin-top: 10px; padding: 16px; border-radius: 12px; background: rgba(255,255,255,.025); border: 1px solid var(--pd-border);">
                <span class="pd-eyebrow" style="margin-bottom: 8px;">SATUAN PENDIDIKAN TEMPAT TUGAS</span>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px;">
                    <div>
                        <span class="pd-label" style="font-size: 9px;">Nama Sekolah</span>
                        <strong style="font-size: 13px; color: #f4f5f7;">{{ $guru->sekolah ? $guru->sekolah->nama_sekolah : '-' }}</strong>
                    </div>
                    <div>
                        <span class="pd-label" style="font-size: 9px;">NPSN</span>
                        <strong style="font-family: monospace; color: #ffbd78;">{{ $guru->sekolah ? $guru->sekolah->npsn : '-' }}</strong>
                    </div>
                    <div>
                        <span class="pd-label" style="font-size: 9px;">Wilayah</span>
                        <span style="color: var(--pd-muted);">{{ $guru->sekolah ? $guru->sekolah->kabupaten : '-' }} ({{ $guru->sekolah ? $guru->sekolah->provinsi : '-' }})</span>
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
                            <strong style="font-size: 11px; color: #f4f5f7;">{{ $log->description }}</strong>
                            <small style="color: var(--pd-dim);">{{ $log->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        
                        @if($log->properties && isset($log->properties['sebelum']))
                            <div style="margin-top: 8px; padding: 10px 12px; border-radius: 8px; background: rgba(0,0,0,.3); border: 1px solid var(--pd-border); font-family: monospace; font-size: 10px; display: flex; flex-direction: column; gap: 4px;">
                                @foreach($log->properties['sesudah'] as $k => $v)
                                    @if(($log->properties['sebelum'][$k] ?? '') != $v)
                                        <div style="color: #94a3b8;">
                                            <span style="color: #64748b;">{{ $k }}:</span>
                                            <span style="text-decoration: line-through; color: #f87171;">{{ $log->properties['sebelum'][$k] ?? '-' }}</span>
                                            <span>→</span>
                                            <strong style="color: #4ade80;">{{ $v ?: '-' }}</strong>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <small style="color: #64748b; font-size: 9px; margin-top: 4px; display: block;">IP: {{ $log->ip_address }}</small>
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
