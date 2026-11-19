@extends('layouts.admin')

@section('title', 'Log Aktivitas Sistem')
@section('page_title', 'Log Aktivitas')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;" x-data="{ detailModal: false, activeLog: null }">

    <!-- Heading -->
    <div class="pd-page-heading" style="margin-bottom: 8px;">
        <div>
            <div class="pd-eyebrow">AUDIT TRAIL & LOG AKTIVITAS</div>
            <h1>Log Aktivitas Sistem<span class="pd-heading-accent">.</span></h1>
            <p>Jejak audit seluruh perubahan data, verifikasi, koreksi, export laporan, dan tindakan sistem lainnya.</p>
        </div>
    </div>

    <!-- Filter & Search Panel -->
    <div class="pd-panel" style="padding: 18px;">
        <form method="GET" action="{{ route('admin.audit.activity') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; align-items: end;">
            <div style="grid-column: span 2;">
                <label class="pd-label" style="font-size: 10px;">Pencarian Bebas</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Aktivitas, Deskripsi, Alamat IP..." class="pd-input" style="height: 36px;">
            </div>

            <div>
                <label class="pd-label" style="font-size: 10px;">Jenis Aksi</label>
                <select name="action" class="pd-select-field" style="height: 36px; font-size: 11px;">
                    <option value="">Semua Jenis Aksi</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ strtoupper($act) }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="pd-button pd-button-primary" style="height: 36px; flex: 1;">Filter</button>
                <a href="{{ route('admin.audit.activity') }}" class="pd-button pd-button-secondary" style="height: 36px;">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table Panel -->
    <section class="pd-panel pd-table-panel">
        <div class="pd-panel-heading">
            <div>
                <h2>Daftar Log Aktivitas</h2>
                <p>Menampilkan total {{ $logs->total() }} baris rekam jejak audit</p>
            </div>
        </div>

        <div class="pd-table-wrap">
            <table class="pd-table">
                <thead>
                    <tr>
                        <th>Waktu (WITA)</th>
                        <th>Aksi</th>
                        <th>Deskripsi Aktivitas</th>
                        <th>Pelaksana / User</th>
                        <th>Alamat IP</th>
                        <th style="text-align: right; width: 100px;">Detail Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td style="color: #cbd5e1; font-family: monospace; font-size: 11px;">
                                {{ $log->created_at ? $log->created_at->timezone('Asia/Makassar')->format('d/m/Y H:i:s') : '-' }}
                            </td>
                            <td>
                                @php
                                    $badgeType = match($log->action) {
                                        'verify_approve', 'login_success' => 'is-success',
                                        'verify_reject', 'soft_delete' => 'is-danger',
                                        'update_guru', 'sync_sekolah' => 'is-warning',
                                        default => 'is-info'
                                    };
                                @endphp
                                <span class="pd-badge {{ $badgeType }}">{{ strtoupper($log->action) }}</span>
                            </td>
                            <td>
                                <strong style="font-size: 12px; color: #f4f5f7;">{{ $log->description }}</strong>
                                @if($log->model_type)
                                    <small style="color: var(--pd-dim); font-size: 9px;">Target: {{ class_basename($log->model_type) }} #{{ $log->model_id }}</small>
                                @endif
                            </td>
                            <td>
                                <strong style="font-size: 11px; color: #cbd5e1;">{{ $log->user ? $log->user->name : 'Sistem / Publik' }}</strong>
                                <small style="color: var(--pd-dim); font-size: 9px;">{{ $log->user ? $log->user->email : '-' }}</small>
                            </td>
                            <td style="font-family: monospace; color: var(--pd-accent); font-size: 11px;">
                                {{ $log->ip_address }}
                            </td>
                            <td style="text-align: right;">
                                @if($log->changes || $log->properties)
                                    <button type="button"
                                            @click="activeLog = {{ json_encode(['action' => $log->action, 'description' => $log->description, 'changes' => $log->changes, 'properties' => $log->properties, 'time' => $log->created_at->timezone('Asia/Makassar')->format('d/m/Y H:i:s')]) }}; detailModal = true;"
                                            class="pd-button pd-button-secondary" style="height: 28px; padding: 0 8px; font-size: 10px;">
                                        Lihat JSON
                                    </button>
                                @else
                                    <span style="color: var(--pd-dim); font-size: 10px;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 48px 16px; color: var(--pd-dim);">
                                Belum ada aktivitas yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid var(--pd-border);">
                {{ $logs->links() }}
            </div>
        @endif
    </section>

    <!-- Modal Detail Perubahan JSON -->
    <div x-show="detailModal" x-cloak style="position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; padding: 16px;">
        <div style="position: fixed; inset: 0; background: rgba(0,0,0,.7); backdrop-filter: blur(4px);" @click="detailModal = false"></div>
        <div class="pd-panel" style="position: relative; z-index: 10; max-width: 600px; width: 100%; padding: 24px;">
            <div class="pd-panel-heading">
                <div>
                    <h2>Detail Audit Trail Data</h2>
                    <p x-text="activeLog ? activeLog.action + ' · ' + activeLog.time : ''"></p>
                </div>
                <button type="button" @click="detailModal = false" style="background: transparent; border: 0; color: #a0aec0; cursor: pointer; font-size: 20px;">×</button>
            </div>
            <div style="margin-top: 14px; max-height: 400px; overflow-y: auto;">
                <p x-text="activeLog ? activeLog.description : ''" style="font-size: 12px; color: #cbd5e1; margin-bottom: 10px;"></p>
                <div style="font-size: 11px; color: var(--pd-accent); font-weight: 700; margin-bottom: 4px;">Data Perubahan (Before / After / Properties):</div>
                <pre style="background: #0d121c; padding: 14px; border-radius: 10px; border: 1px solid var(--pd-border); color: #4ade80; font-family: monospace; font-size: 11px; white-space: pre-wrap; word-break: break-all;" x-text="JSON.stringify(activeLog ? (activeLog.changes || activeLog.properties) : {}, null, 2)"></pre>
            </div>
            <div style="display: flex; justify-content: flex-end; margin-top: 16px;">
                <button type="button" @click="detailModal = false" class="pd-button pd-button-secondary">Tutup</button>
            </div>
        </div>
    </div>

</div>
@endsection
