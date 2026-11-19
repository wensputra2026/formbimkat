@extends('layouts.admin')

@section('title', 'Koreksi Data Guru — ' . $guru->nama)
@section('page_title', 'Koreksi Data Guru')

@section('content')
<div style="width: 100%; display: flex; flex-direction: column; gap: 20px;">

    <!-- Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('admin.guru.show', $guru->id) }}" class="pd-button pd-button-secondary" style="height: 36px;">
            <span>← Kembali ke Detail</span>
        </a>
        <span class="pd-badge is-warning" style="font-family: monospace; font-size: 11px;">
            {{ $guru->nomor_registrasi }}
        </span>
    </div>

    @if ($errors->any())
        <div class="pd-alert pd-alert-error">
            <strong style="display: block; margin-bottom: 4px;">Terdapat kesalahan isian:</strong>
            <ul style="margin: 0; padding-left: 18px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" class="pd-panel" style="padding: clamp(20px, 4vw, 36px); display: flex; flex-direction: column; gap: 28px;">
        @csrf
        @method('PUT')

        <!-- Bagian 1: Identitas -->
        <div>
            <div style="padding-bottom: 10px; border-bottom: 1px solid var(--pd-border); margin-bottom: 16px;">
                <h3 style="margin: 0; font-size: 14px; font-weight: 800; color: #0f172a;">1. Identitas Guru</h3>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                <div style="grid-column: 1 / -1;">
                    <label for="nama" class="pd-label">Nama Lengkap <em>*</em></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $guru->nama) }}" required class="pd-input">
                </div>
                <div>
                    <label for="nip" class="pd-label">NIP (18 Digit)</label>
                    <input type="text" inputmode="numeric" maxlength="18" name="nip" id="nip" value="{{ old('nip', $guru->nip) }}" class="pd-input" style="font-family: monospace;">
                </div>
                <div>
                    <label for="nik" class="pd-label">NIK (16 Digit)</label>
                    <input type="text" inputmode="numeric" maxlength="16" name="nik" id="nik" value="{{ old('nik', $guru->nik) }}" class="pd-input" style="font-family: monospace;">
                </div>
            </div>
        </div>

        <!-- Bagian 2: Pangkat & TPG -->
        <div>
            <div style="padding-bottom: 10px; border-bottom: 1px solid var(--pd-border); margin-bottom: 16px;">
                <h3 style="margin: 0; font-size: 14px; font-weight: 800; color: #0f172a;">2. Pangkat & Kategori TPG</h3>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                <div>
                    <label for="pangkat_golongan_id" class="pd-label">Pangkat / Golongan <em>*</em></label>
                    <select name="pangkat_golongan_id" id="pangkat_golongan_id" required class="pd-select-field">
                        @foreach($pangkats as $p)
                            <option value="{{ $p->id }}" {{ old('pangkat_golongan_id', $guru->pangkat_golongan_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->golongan }} — {{ $p->pangkat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="penerima_tpg" class="pd-label">Kategori TPG</label>
                    <select name="penerima_tpg" id="penerima_tpg" class="pd-select-field">
                        <option value="">-- Belum Ditentukan --</option>
                        @foreach($tpgChoices as $tpg)
                            <option value="{{ $tpg }}" {{ old('penerima_tpg', $guru->penerima_tpg) == $tpg ? 'selected' : '' }}>
                                {{ $tpg }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Bagian 3: Gaji Pokok, KGB & Pensiun -->
        <div>
            <div style="padding-bottom: 10px; border-bottom: 1px solid var(--pd-border); margin-bottom: 16px;">
                <h3 style="margin: 0; font-size: 14px; font-weight: 800; color: #0f172a;">3. Gaji Pokok, KGB & Pensiun</h3>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                <div>
                    <label for="gaji_pokok" class="pd-label">Gaji Pokok (Rp) <em>*</em></label>
                    <input type="number" step="0.01" min="0" name="gaji_pokok" id="gaji_pokok" value="{{ old('gaji_pokok', $guru->gaji_pokok) }}" required class="pd-input" style="font-family: monospace;">
                </div>
                <div>
                    <label class="pd-label">KGB Berikutnya <em>*</em></label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                        <select name="kgb_bulan" required class="pd-select-field">
                            @foreach($bulanChoices as $n => $b)
                                <option value="{{ $n }}" {{ old('kgb_bulan', $guru->kgb_bulan) == $n ? 'selected' : '' }}>{{ $b }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="kgb_tahun" min="1900" max="2100" value="{{ old('kgb_tahun', $guru->kgb_tahun) }}" required class="pd-input">
                    </div>
                </div>
                <div>
                    <label class="pd-label">Pensiun <em>*</em></label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                        <select name="pensiun_bulan" required class="pd-select-field">
                            @foreach($bulanChoices as $n => $b)
                                <option value="{{ $n }}" {{ old('pensiun_bulan', $guru->pensiun_bulan) == $n ? 'selected' : '' }}>{{ $b }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="pensiun_tahun" min="1900" max="2100" value="{{ old('pensiun_tahun', $guru->pensiun_tahun) }}" required class="pd-input">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian 4: Sekolah Tempat Tugas -->
        <div>
            <div style="padding-bottom: 10px; border-bottom: 1px solid var(--pd-border); margin-bottom: 16px;">
                <h3 style="margin: 0; font-size: 14px; font-weight: 800; color: #0f172a;">4. Sekolah / Tempat Tugas</h3>
            </div>
            <div x-data="schoolSearch('{{ old('sekolah_nama', $guru->sekolah ? $guru->sekolah->nama_sekolah : '') }}', '{{ old('sekolah_id', $guru->sekolah_id) }}')" style="position: relative;">
                <input type="hidden" name="sekolah_id" :value="selectedId" required>
                <input type="hidden" name="sekolah_nama" :value="query">

                <div style="position: relative;">
                    <input type="text"
                           x-model="query"
                           @input.debounce.300ms="search()"
                           @focus="if(query.length >= 2) open = true"
                           placeholder="Ketik minimal 2 huruf nama sekolah atau NPSN..."
                           class="pd-input"
                           style="padding-right: 36px;"
                           autocomplete="off">

                    <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%);">
                        <span x-show="loading" style="color: var(--pd-accent); font-size: 12px; display: none;">⏳</span>
                        <button type="button" x-show="query && !loading" @click="clear()" style="background: transparent; border: 0; color: #94a3b8; cursor: pointer; font-size: 16px; display: none;">×</button>
                    </div>
                </div>

                <!-- Dropdown -->
                <div x-show="open && results.length > 0"
                     @click.away="open = false"
                     style="position: absolute; z-index: 50; width: 100%; margin-top: 6px; background: #fff; border: 1px solid #86c99e; border-radius: 12px; max-height: 240px; overflow-y: auto; box-shadow: 0 16px 40px rgba(15,23,42,.16); display: none;">
                    <template x-for="item in results" :key="item.id_sekolah">
                        <div @click="choose(item)" style="padding: 10px 14px; border-bottom: 1px solid #e2e8f0; cursor: pointer;"
                             onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='#fff'">
                            <strong style="font-size: 12px; color: #0f172a; display: block;" x-text="item.nama_sekolah"></strong>
                            <small style="color: #64748b; font-size: 10px;" x-text="'NPSN: ' + (item.npsn || '-') + ' | ' + item.kabupaten"></small>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 18px; border-top: 1px solid var(--pd-border);">
            <small style="color: var(--pd-muted); font-style: italic;">Perubahan nilai sebelum & sesudah akan dicatat di log audit.</small>
            <button type="submit" class="pd-button pd-button-primary" style="height: 42px;">
                <span>Simpan Perbaikan Data</span>
                <span>✓</span>
            </button>
        </div>
    </form>
</div>
@endsection
