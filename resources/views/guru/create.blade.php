@extends('layouts.public')

@section('title', 'Formulir Pendataan Guru Mandiri')

@section('content')
<section class="pub-section" style="padding-top: 35px; padding-bottom: 60px;">
<div class="pub-container" style="display: flex; flex-direction: column; gap: 28px;" x-data="duplicateChecker()">

    <!-- Header Banner -->
    <div class="pd-panel" style="padding: clamp(24px, 4vw, 36px);">
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="pub-badge-year">
                    <i></i> TAHUN PENDATAAN {{ \App\Models\Setting::get('tahun_pendataan', date('Y')) }}
                </span>
            </div>
            <h1 style="margin: 0; font-size: clamp(22px, 3.5vw, 32px); font-weight: 800; letter-spacing: -0.03em;">
                Formulir Pendataan Guru Mandiri<span style="color: var(--pub-accent);">.</span>
            </h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="pd-alert pd-alert-error" style="border-radius: 12px; font-size: 12px;">
            <strong style="display: block; margin-bottom: 6px; font-weight: 700;">Periksa kembali isian formulir Anda:</strong>
            <ul style="margin: 0; padding-left: 18px; line-height: 1.5;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Utama -->
    <form action="{{ route('guru.store') }}" method="POST" class="pd-panel" data-swal-title="Kirim formulir pendataan?" data-swal-confirm="Pastikan seluruh data yang Anda isi sudah benar dan lengkap." data-swal-icon="question" data-swal-confirm-button="Ya, kirim" style="padding: clamp(20px, 4vw, 36px); display: flex; flex-direction: column; gap: 32px;">
        @csrf

        <!-- 1. IDENTITAS GURU -->
        <div>
            <div class="pub-step-header">
                <span class="pub-step-num">1</span>
                <div>
                    <h3 class="pub-step-title">Identitas Pribadi Guru</h3>
                    <small class="pub-step-desc">Nama lengkap dan nomor identitas kependudukan/kepegawaian</small>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px;">
                <!-- Nama Lengkap -->
                <div style="grid-column: 1 / -1;">
                    <label for="nama" class="pd-label">Nama Lengkap Guru <em>*</em></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required maxlength="150" placeholder="Contoh: Maria Magdalena, S.Pd" class="pd-input">
                    <span class="pd-hint">Nama lengkap beserta gelar akademik jika ada.</span>
                </div>

                <!-- NIP -->
                <div>
                    <label for="nip" class="pd-label">NIP (Nomor Induk Pegawai)</label>
                    <input type="text" inputmode="numeric" pattern="[0-9]{18}" name="nip" id="nip" value="{{ old('nip') }}" maxlength="18" placeholder="18 digit angka (khusus PNS/PPPK)"
                           x-model="nip"
                           @blur="check('nip')"
                           class="pd-input" style="font-family: monospace; letter-spacing: 0.05em;">
                    <span class="pd-hint">Tepat 18 digit angka tanpa spasi. Kosongkan jika Non-PNS.</span>
                    <p x-show="nipMessage" x-text="nipMessage" :style="nipError ? 'color: #dc2626;' : 'color: #16a34a;'" style="margin: 4px 0 0; font-size: 11px; font-weight: 600; display: none;"></p>
                </div>

                <!-- NIK -->
                <div>
                    <label for="nik" class="pd-label">NIK (Nomor Induk Kependudukan)</label>
                    <input type="text" inputmode="numeric" pattern="[0-9]{16}" name="nik" id="nik" value="{{ old('nik') }}" maxlength="16" placeholder="16 digit angka sesuai KTP/KK"
                           x-model="nik"
                           @blur="check('nik')"
                           class="pd-input" style="font-family: monospace; letter-spacing: 0.05em;">
                    <span class="pd-hint">Tepat 16 digit angka sesuai dokumen KTP/KK resmi.</span>
                    <p x-show="nikMessage" x-text="nikMessage" :style="nikError ? 'color: #dc2626;' : 'color: #16a34a;'" style="margin: 4px 0 0; font-size: 11px; font-weight: 600; display: none;"></p>
                </div>
            </div>

            <div class="pub-notice-box">
                💡 <strong>Ketentuan:</strong> Minimal salah satu dari NIP atau NIK wajib diisi. NIP tepat 18 digit angka (khusus PNS/PPPK), NIK tepat 16 digit angka sesuai KTP/KK.
            </div>
        </div>

        <!-- 2. STATUS KEPEGAWAIAN & TPG -->
        <div>
            <div class="pub-step-header">
                <span class="pub-step-num">2</span>
                <div>
                    <h3 class="pub-step-title">Pangkat, Golongan & Status TPG</h3>
                    <small class="pub-step-desc">Pilih sesuai SK pengangkatan atau surat tugas resmi</small>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
                <!-- Pangkat / Golongan -->
                <div>
                    <label for="pangkat_golongan_id" class="pd-label">Pangkat / Golongan <em>*</em></label>
                    <select name="pangkat_golongan_id" id="pangkat_golongan_id" required class="pd-select-field">
                        <option value="">-- Pilih Pangkat / Golongan --</option>
                        @foreach($pangkats as $p)
                            <option value="{{ $p->id }}" {{ old('pangkat_golongan_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->golongan }} — {{ $p->pangkat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Penerima TPG -->
                <div>
                    <label for="penerima_tpg" class="pd-label">Kategori Penerima TPG</label>
                    <select name="penerima_tpg" id="penerima_tpg" class="pd-select-field">
                        <option value="">-- Belum Ditentukan / Tidak Menerima --</option>
                        @foreach($tpgChoices as $tpg)
                            <option value="{{ $tpg }}" {{ old('penerima_tpg') == $tpg ? 'selected' : '' }}>
                                {{ $tpg }}
                            </option>
                        @endforeach
                    </select>
                    <span class="pd-hint">Pilihan dibatasi sesuai 6 kategori resmi TPG.</span>
                </div>
            </div>
        </div>

        <!-- 3. GAJI POKOK, KGB & PENSIUN -->
        <div>
            <div class="pub-step-header">
                <span class="pub-step-num">3</span>
                <div>
                    <h3 class="pub-step-title">Gaji Pokok, KGB & Pensiun</h3>
                    <small class="pub-step-desc">Nominal gaji pokok dan estimasi berkala serta batas usia pensiun</small>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px;">
                <!-- Gaji Pokok -->
                <div>
                    <label for="gaji_pokok" class="pd-label">Gaji Pokok Saat Ini (Rp) <em>*</em></label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-weight: 800; color: var(--pub-accent-dark); font-size: 12px;">Rp</span>
                        <input type="number" min="0" step="0.01" name="gaji_pokok" id="gaji_pokok" value="{{ old('gaji_pokok') }}" required placeholder="Contoh: 3500000" class="pd-input" style="padding-left: 40px; font-family: monospace;">
                    </div>
                    <span class="pd-hint">Masukkan angka murni tanpa tanda titik atau koma ribuan.</span>
                </div>

                <!-- KGB Berikutnya -->
                <div>
                    <label class="pd-label">Berkala Berikutnya (KGB) <em>*</em></label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                        <select name="kgb_bulan" id="kgb_bulan" required class="pd-select-field">
                            <option value="">Bulan</option>
                            @foreach($bulanChoices as $num => $namaBulan)
                                <option value="{{ $num }}" {{ old('kgb_bulan') == $num ? 'selected' : '' }}>{{ $namaBulan }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="kgb_tahun" id="kgb_tahun" min="1900" max="2100" placeholder="Tahun" value="{{ old('kgb_tahun', date('Y')) }}" required class="pd-input">
                    </div>
                    <span class="pd-hint">Pilih bulan dan masukkan 4 digit tahun berkala.</span>
                </div>

                <!-- Batas Usia Pensiun -->
                <div>
                    <label class="pd-label">Perkiraan Pensiun <em>*</em></label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                        <select name="pensiun_bulan" id="pensiun_bulan" required class="pd-select-field">
                            <option value="">Bulan</option>
                            @foreach($bulanChoices as $num => $namaBulan)
                                <option value="{{ $num }}" {{ old('pensiun_bulan') == $num ? 'selected' : '' }}>{{ $namaBulan }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="pensiun_tahun" id="pensiun_tahun" min="1900" max="2100" placeholder="Tahun" value="{{ old('pensiun_tahun', date('Y') + 15) }}" required class="pd-input">
                    </div>
                    <span class="pd-hint">Bulan dan 4 digit tahun pensiun.</span>
                </div>
            </div>
        </div>

        <!-- 4. SATUAN PENDIDIKAN / SEKOLAH -->
        <div>
            <div class="pub-step-header">
                <span class="pub-step-num">4</span>
                <div>
                    <h3 class="pub-step-title">Satuan Pendidikan / Tempat Tugas</h3>
                    <small class="pub-step-desc">Pilih sekolah dari Master Sekolah resmi BIMKAT</small>
                </div>
            </div>

            <!-- Searchable Dropdown Sekolah -->
            <div x-data="schoolSearch('{{ old('sekolah_nama') }}', '{{ old('sekolah_id') }}')" style="position: relative;">
                <label for="sekolah_search" class="pd-label">Cari & Pilih Sekolah Tempat Tugas <em>*</em></label>
                
                <input type="hidden" name="sekolah_id" :value="selectedId" required>
                <input type="hidden" name="sekolah_nama" :value="query">

                <div style="position: relative;">
                    <input type="text"
                           id="sekolah_search"
                           x-model="query"
                           @input.debounce.300ms="search()"
                           @focus="if(query.length >= 2) open = true"
                           placeholder="Ketik minimal 2 huruf nama sekolah atau NPSN..."
                           class="pd-input"
                           style="padding-right: 40px;"
                           autocomplete="off">

                    <!-- Status Indicator -->
                    <div style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%);">
                        <span x-show="loading" style="color: var(--pub-accent); font-size: 13px; display: none;">⏳</span>
                        <button type="button" x-show="query && !loading" @click="clear()" style="background: transparent; border: 0; color: var(--pub-muted); cursor: pointer; font-size: 18px; display: none;">×</button>
                    </div>
                </div>

                <!-- Dropdown Hasil Pencarian -->
                <div x-show="open && results.length > 0"
                     @click.away="open = false"
                     class="pub-search-dropdown"
                     style="display: none;">
                    <template x-for="item in results" :key="item.id_sekolah">
                        <div @click="choose(item)" class="pub-search-item">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <strong x-text="item.nama_sekolah"></strong>
                                <span class="pub-badge-year" style="font-size: 10px; padding: 2px 8px;" x-text="item.jenjang || 'Sekolah'"></span>
                            </div>
                            <div style="font-size: 11px; margin-top: 4px; display: flex; gap: 12px;">
                                <span x-show="item.npsn">NPSN: <b x-text="item.npsn"></b></span>
                                <span x-show="item.kabupaten" x-text="item.kabupaten"></span>
                                <span x-show="item.provinsi" x-text="'(' + item.provinsi + ')'"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Info Box If Selected -->
                <div x-show="selectedId" class="pub-selected-school" style="display: none;">
                    <div>
                        <span style="font-weight: 700;">✓ Sekolah Terpilih:</span>
                        <strong style="margin-left: 4px;" x-text="query"></strong>
                    </div>
                    <button type="button" @click="clear()" style="background: transparent; border: 0; color: #ef4444; font-size: 12px; font-weight: 700; cursor: pointer;">Ganti</button>
                </div>

                <span class="pd-hint">Sekolah tersinkronisasi langsung dari database BIMKAT.</span>
            </div>
        </div>

        <!-- Tombol Kirim -->
        <div style="display: flex; flex-direction: column; gap: 14px; padding-top: 20px; border-top: 1px solid var(--pub-line);">
            <div style="font-size: 12px; color: var(--pub-muted); text-align: center;">
                Periksa kembali data Anda. Setelah formulir dikirim, nomor registrasi akan diterbitkan dan perbaikan hanya dapat dilakukan oleh Admin.
            </div>

            <button type="submit"
                    class="pub-button pub-button-primary pub-button-large"
                    style="width: 100%; font-size: 14px; font-weight: 800; letter-spacing: 0.05em;">
                <span>KIRIM FORMULIR PENDATAAN SEKARANG</span>
                <span>→</span>
            </button>
        </div>
    </form>
</div>
</section>
@endsection
