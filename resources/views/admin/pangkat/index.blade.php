@extends('layouts.admin')

@section('title', 'Master Pangkat & Golongan')
@section('page_title', 'Master Pangkat & Golongan')

@section('content')
<div style="display: flex; flex-direction: column; gap: 20px;" x-data="{ createModal: false, editModal: false, activePangkat: { id: '', golongan: '', pangkat: '', urutan: 0, is_active: true } }">

    <!-- Heading -->
    <div class="pd-page-heading" style="margin-bottom: 8px;">
        <div>
            <div class="pd-eyebrow">PENGATURAN RUANG & PANGKAT</div>
            <h1>Master Pangkat / Golongan<span class="pd-heading-accent">.</span></h1>
            <p>Daftar pangkat dan golongan ruang kepegawaian ASN dan Non-ASN yang digunakan pada formulir guru.</p>
        </div>
        <div class="pd-heading-actions">
            <button type="button" @click="createModal = true" class="pd-button pd-button-primary">
                <span>＋ Tambah Golongan Baru</span>
            </button>
        </div>
    </div>

    <!-- Tabel Panel -->
    <section class="pd-panel pd-table-panel">
        <div class="pd-panel-heading">
            <div>
                <h2>Daftar Golongan Ruang</h2>
                <p>Total {{ $pangkats->count() }} golongan ruang terdaftar pada sistem</p>
            </div>
        </div>

        <div class="pd-table-wrap">
            <table class="pd-table">
                <thead>
                    <tr>
                        <th style="width: 70px; text-align: center;">Urutan</th>
                        <th>Golongan Ruang</th>
                        <th>Nama Pangkat</th>
                        <th style="text-align: center;">Jumlah Guru Terdata</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: right; width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pangkats as $p)
                        <tr>
                            <td style="text-align: center; color: var(--pd-dim); font-family: monospace;">{{ $p->urutan }}</td>
                            <td>
                                <strong style="font-size: 13px; color: var(--pd-accent); font-family: monospace;">{{ $p->golongan }}</strong>
                            </td>
                            <td>
                                <strong style="font-size: 12px; color: #f4f5f7;">{{ $p->pangkat }}</strong>
                            </td>
                            <td style="text-align: center;">
                                <span class="pd-badge is-info">{{ $p->gurus_count }} Orang</span>
                            </td>
                            <td style="text-align: center;">
                                @if($p->is_active)
                                    <span class="pd-badge is-success"><i></i> Aktif</span>
                                @else
                                    <span class="pd-badge is-warning"><i></i> Non-Aktif</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <button type="button"
                                            @click="activePangkat = { id: {{ $p->id }}, golongan: '{{ addslashes($p->golongan) }}', pangkat: '{{ addslashes($p->pangkat) }}', urutan: {{ $p->urutan }}, is_active: {{ $p->is_active ? 'true' : 'false' }} }; editModal = true;"
                                            class="pd-button pd-button-secondary" style="height: 28px; padding: 0 8px; font-size: 10px;">
                                        Edit
                                    </button>

                                    @if($p->gurus_count === 0)
                                        <form action="{{ route('admin.pangkat.destroy', $p->id) }}" method="POST" data-swal-title="Hapus golongan?" data-swal-confirm="Golongan {{ $p->golongan }} akan dihapus." data-swal-confirm-button="Ya, hapus" style="margin: 0; display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="pd-button pd-button-danger" style="height: 28px; padding: 0 8px; font-size: 10px;">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--pd-dim);">
                                Belum ada data pangkat/golongan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Modal Tambah Pangkat -->
    <div x-show="createModal" x-cloak style="position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; padding: 16px;">
        <div style="position: fixed; inset: 0; background: rgba(0,0,0,.7); backdrop-filter: blur(4px);" @click="createModal = false"></div>
        <div class="pd-panel" style="position: relative; z-index: 10; max-width: 480px; width: 100%; padding: 24px;">
            <div class="pd-panel-heading">
                <h2>Tambah Golongan Baru</h2>
                <button type="button" @click="createModal = false" style="background: transparent; border: 0; color: #a0aec0; cursor: pointer; font-size: 20px;">×</button>
            </div>
            <form action="{{ route('admin.pangkat.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 14px; margin-top: 14px;">
                @csrf
                <div>
                    <label class="pd-label">Golongan Ruang <em>*</em></label>
                    <input type="text" name="golongan" required placeholder="Contoh: III/a atau IX" class="pd-input">
                </div>
                <div>
                    <label class="pd-label">Nama Pangkat <em>*</em></label>
                    <input type="text" name="pangkat" required placeholder="Contoh: Penata Muda" class="pd-input">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="pd-label">Nomor Urutan</label>
                        <input type="number" name="urutan" value="1" min="0" class="pd-input">
                    </div>
                    <div>
                        <label class="pd-label">Status</label>
                        <select name="is_active" class="pd-select-field">
                            <option value="1">Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 10px;">
                    <button type="button" @click="createModal = false" class="pd-button pd-button-secondary">Batal</button>
                    <button type="submit" class="pd-button pd-button-primary">Simpan Golongan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Pangkat -->
    <div x-show="editModal" x-cloak style="position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; padding: 16px;">
        <div style="position: fixed; inset: 0; background: rgba(0,0,0,.7); backdrop-filter: blur(4px);" @click="editModal = false"></div>
        <div class="pd-panel" style="position: relative; z-index: 10; max-width: 480px; width: 100%; padding: 24px;">
            <div class="pd-panel-heading">
                <h2>Edit Golongan Ruang</h2>
                <button type="button" @click="editModal = false" style="background: transparent; border: 0; color: #a0aec0; cursor: pointer; font-size: 20px;">×</button>
            </div>
            <form :action="'{{ url('/admin/pangkat') }}/' + activePangkat.id" method="POST" style="display: flex; flex-direction: column; gap: 14px; margin-top: 14px;">
                @csrf
                @method('PUT')
                <div>
                    <label class="pd-label">Golongan Ruang <em>*</em></label>
                    <input type="text" name="golongan" x-model="activePangkat.golongan" required class="pd-input">
                </div>
                <div>
                    <label class="pd-label">Nama Pangkat <em>*</em></label>
                    <input type="text" name="pangkat" x-model="activePangkat.pangkat" required class="pd-input">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="pd-label">Nomor Urutan</label>
                        <input type="number" name="urutan" x-model="activePangkat.urutan" min="0" class="pd-input">
                    </div>
                    <div>
                        <label class="pd-label">Status</label>
                        <select name="is_active" x-model="activePangkat.is_active" class="pd-select-field">
                            <option value="1">Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 10px;">
                    <button type="button" @click="editModal = false" class="pd-button pd-button-secondary">Batal</button>
                    <button type="submit" class="pd-button pd-button-primary">Perbarui Golongan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
