@extends('layouts.admin')

@section('title', 'Data Guru Kepegawaian')
@section('page_title', 'Kelola Data Guru')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;">

    <!-- Heading -->
    <div class="pd-page-heading" style="margin-bottom: 8px;">
        <div>
            <div class="pd-eyebrow">PENGELOLAAN GURU</div>
            <h1>Daftar & Status Data Guru<span class="pd-heading-accent">.</span></h1>
            <p>Seluruh data guru bersumber langsung dari formulir mandiri yang diisi oleh guru bersangkutan.</p>
        </div>
        <div class="pd-heading-actions">
            <a href="{{ route('admin.report.export_excel') }}" class="pd-button pd-button-primary">
                <span>EXPORT EXCEL (6 SHEET)</span>
            </a>
        </div>
    </div>

    <!-- Submenu Tabs -->
    <div style="display: flex; flex-wrap: wrap; gap: 8px; border-bottom: 1px solid var(--pd-border); padding-bottom: 14px;">
        <a href="{{ route('admin.guru.index', ['tab' => 'semua']) }}"
           class="pd-button {{ $statusTab === 'semua' ? 'pd-button-primary' : 'pd-button-secondary' }}" style="height: 36px;">
            <span>Semua Data Guru</span>
            <span style="padding: 2px 7px; border-radius: 10px; font-size: 10px; font-weight: 800; background: rgba(0,0,0,.15); margin-left: 4px;">{{ $counts['semua'] }}</span>
        </a>

        <a href="{{ route('admin.guru.index', ['tab' => 'trash']) }}"
           class="pd-button {{ $statusTab === 'trash' ? 'pd-button-primary' : 'pd-button-secondary' }}" style="height: 36px;">
            <span>Arsip Data (Terhapus)</span>
            <span style="padding: 2px 7px; border-radius: 10px; font-size: 10px; font-weight: 800; background: rgba(0,0,0,.15); margin-left: 4px;">{{ $counts['trash'] }}</span>
        </a>
    </div>

    <!-- Filter & Search Panel -->
    <div class="pd-panel" style="padding: 18px;">
        <form method="GET" action="{{ route('admin.guru.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; align-items: end;">
            <input type="hidden" name="tab" value="{{ $statusTab }}">

            <!-- Search -->
            <div style="grid-column: span 2;">
                <label class="pd-label" style="font-size: 10px;">Pencarian Bebas</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari Nama, NIP, NIK, No. Registrasi..." class="pd-input" style="height: 36px;">
            </div>

            <!-- Filter Pangkat -->
            <div>
                <label class="pd-label" style="font-size: 10px;">Pangkat / Golongan</label>
                <select name="pangkat_id" class="pd-select-field" style="height: 36px; font-size: 11px;">
                    <option value="">Semua Pangkat</option>
                    @foreach($pangkats as $p)
                        <option value="{{ $p->id }}" {{ request('pangkat_id') == $p->id ? 'selected' : '' }}>{{ $p->golongan }} - {{ $p->pangkat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter TPG -->
            <div>
                <label class="pd-label" style="font-size: 10px;">Kategori TPG</label>
                <select name="tpg" class="pd-select-field" style="height: 36px; font-size: 11px;">
                    <option value="">Semua TPG</option>
                    @foreach(\App\Models\Guru::getTpgChoices() as $tpg)
                        <option value="{{ $tpg }}" {{ request('tpg') == $tpg ? 'selected' : '' }}>{{ $tpg }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tombol Submit & Reset -->
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="pd-button pd-button-primary" style="height: 36px; flex: 1;">
                    <span>Filter</span>
                </button>
                <a href="{{ route('admin.guru.index', ['tab' => $statusTab]) }}" class="pd-button pd-button-secondary" style="height: 36px;">
                    <span>Reset</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Panel -->
    <section class="pd-panel pd-table-panel">
        <div class="pd-panel-heading">
            <div>
                <h2>Tabel Master Data Guru</h2>
                <p>Menampilkan total {{ $gurus->total() }} baris data pada sistem</p>
            </div>
        </div>

        <div class="pd-table-wrap">
            <table class="pd-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">NO.</th>
                        <th>NO. REGISTRASI</th>
                        <th>NAMA GURU</th>
                        <th>IDENTITAS (NIP / NIK)</th>
                        <th>SEKOLAH TEMPAT TUGAS</th>
                        <th>PANGKAT & GOLONGAN</th>
                        <th>PENERIMA TPG</th>
                        <th style="text-align: center;">STATUS</th>
                        <th style="text-align: right; width: 140px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $index => $guru)
                        <tr>
                            <td style="text-align: center; color: #64748b; font-weight: 700;">
                                {{ $gurus->firstItem() + $index }}
                            </td>
                            <td>
                                <strong style="font-family: monospace; color: #ea580c; font-size: 13px; font-weight: 800;">{{ $guru->nomor_registrasi }}</strong>
                                <small style="color: #64748b; font-weight: 500;">{{ $guru->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.guru.show', $guru->id) }}" style="color: #0f172a; font-weight: 800; font-size: 13.5px; text-decoration: none; display: block;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#0f172a'">
                                    {{ $guru->nama }}
                                </a>
                                <small style="color: #047857; font-weight: 700; font-size: 11px;">{{ $guru->formatted_gaji }}</small>
                            </td>
                            <td>
                                @if($guru->nip)
                                    <div style="font-family: monospace; color: #0f172a; font-weight: 600; font-size: 11.5px;">NIP: {{ $guru->nip }}</div>
                                @endif
                                @if($guru->nik)
                                    <div style="font-family: monospace; color: #475569; font-weight: 500; font-size: 10.5px;">NIK: {{ $guru->nik }}</div>
                                @endif
                            </td>
                            <td>
                                <strong style="color: #0f172a; font-size: 13px;">{{ $guru->sekolah ? $guru->sekolah->nama_sekolah : '-' }}</strong>
                                <small style="color: #64748b; font-size: 11px;">{{ $guru->sekolah ? $guru->sekolah->kabupaten : '' }}</small>
                            </td>
                            <td>
                                <strong style="color: #0f172a; font-size: 13px;">{{ $guru->pangkatGolongan ? $guru->pangkatGolongan->golongan : '-' }}</strong>
                                <small style="color: #64748b; font-size: 11px;">{{ $guru->pangkatGolongan ? $guru->pangkatGolongan->pangkat : '-' }}</small>
                            </td>
                            <td>
                                @if($guru->penerima_tpg)
                                    <span class="pd-badge is-info"><i></i>{{ $guru->penerima_tpg }}</span>
                                @else
                                    <span style="color: #64748b; font-style: italic;">-</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($guru->status_verifikasi === 'Disetujui')
                                    <span class="pd-badge is-success"><i></i> Disetujui</span>
                                @elseif($guru->status_verifikasi === 'Ditolak')
                                    <span class="pd-badge is-danger"><i></i> Ditolak</span>
                                @else
                                    <span class="pd-badge is-warning"><i></i> Menunggu</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    @if($guru->trashed())
                                        <form action="{{ route('admin.guru.restore', $guru->id) }}" method="POST" data-swal-title="Pulihkan data guru?" data-swal-confirm="Data guru akan dikembalikan ke daftar aktif." data-swal-icon="question" data-swal-confirm-button="Ya, pulihkan">
                                            @csrf
                                            <button type="submit" class="pd-button pd-button-success" style="font-size: 10px; height: 26px; padding: 0 8px;">
                                                Pulihkan
                                            </button>
                                        </form>
                                    @else
                                        <!-- Detail -->
                                        <a href="{{ route('admin.guru.show', $guru->id) }}" class="pd-button pd-button-secondary" style="font-size: 10px; height: 26px; padding: 0 8px;" title="Lihat Detail">
                                            Lihat
                                        </a>

                                        <!-- Koreksi -->
                                        <a href="{{ route('admin.guru.edit', $guru->id) }}" class="pd-button pd-button-warning" style="font-size: 10px; height: 26px; padding: 0 8px;" title="Koreksi Data">
                                            Koreksi
                                        </a>

                                        <!-- Soft Delete -->
                                        <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST" data-swal-title="Arsipkan data guru?" data-swal-confirm="Data guru akan dipindahkan ke arsip." data-swal-confirm-button="Ya, arsipkan" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="pd-button pd-button-danger" style="font-size: 10px; height: 26px; padding: 0 8px;" title="Arsipkan">
                                                ✕
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 36px; color: #64748b; font-style: italic;">
                                Tidak ada data guru yang sesuai dengan kriteria filter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($gurus->hasPages())
            <div style="padding: 16px; border-top: 1px solid var(--pd-border);">
                {{ $gurus->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
