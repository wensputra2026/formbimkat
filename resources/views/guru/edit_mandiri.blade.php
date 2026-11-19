@extends('layouts.public')

@section('title', 'Perbaiki Formulir Pendataan — ' . $guru->nomor_registrasi)

@section('content')
<section class="pub-section" style="padding-top: 25px; padding-bottom: 50px;">
<div class="pub-container" style="display: flex; flex-direction: column; gap: 22px;">

    <!-- Header Banner -->
    <div class="pd-panel" style="padding: clamp(20px, 3.5vw, 32px); background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px;">
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <span class="pub-badge-year" style="background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa;">
                    <i></i> KOREKSI MANDIRI DATA GURU
                </span>
                <span style="font-family: monospace; font-size: 13px; font-weight: 800; color: #ea580c; background: #fff7ed; padding: 4px 12px; border-radius: 8px; border: 1px solid #fed7aa;">
                    {{ $guru->nomor_registrasi }}
                </span>
            </div>
            <h1 style="margin: 0; font-size: clamp(20px, 3.5vw, 26px); font-weight: 800; letter-spacing: -0.03em; color: #0f172a;">
                Perbaiki Isian Formulir Pendataan Guru
            </h1>
            <p style="margin: 4px 0 0; color: #475569; font-size: 13.5px; line-height: 1.6;">
                Silakan lakukan koreksi pada bagian data yang salah diisi. Setelah selesai, klik tombol <strong>"SIMPAN PERBAIKAN DATA"</strong> di bawah.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="pd-alert pd-alert-error" style="border-radius: 12px; font-size: 13px;">
            <strong style="display: block; margin-bottom: 6px; font-weight: 700;">Terdapat kesalahan pada data yang Anda masukkan:</strong>
            <ul style="margin: 0; padding-left: 18px; line-height: 1.5;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Utama Edit Mandiri -->
    <form action="{{ route('guru.update_mandiri', $guru->nomor_registrasi) }}" method="POST" class="pd-panel" data-swal-title="Simpan perbaikan data formulir?" data-swal-confirm="Pastikan data perbaikan yang Anda masukkan sudah benar." data-swal-icon="question" data-swal-confirm-button="Ya, simpan perbaikan" style="padding: clamp(16px, 3.5vw, 32px); display: flex; flex-direction: column; gap: 28px; width: 100%; box-sizing: border-box; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px;">
        @csrf
        @method('PUT')

        <!-- 1. IDENTITAS GURU -->
        <div>
            <div class="pub-step-header">
                <span class="pub-step-num">1</span>
                <div>
                    <h3 class="pub-step-title" style="color: #0f172a;">Identitas Pribadi Guru</h3>
                    <small class="pub-step-desc" style="color: #64748b;">Nama lengkap dan nomor identitas kepegawaian/kependudukan</small>
                </div>
            </div>

            <div class="pd-form-grid-2">
                <!-- Nama Lengkap -->
                <div style="grid-column: 1 / -1;">
                    <label for="nama" class="pd-label" style="color: #0f172a; font-weight: 700;">Nama Lengkap Guru <em>*</em></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $guru->nama) }}" required maxlength="150" placeholder="Contoh: Maria Magdalena, S.Pd" class="pd-input" style="background: #ffffff; color: #0f172a; border-color: #cbd5e1;">
                    <span class="pd-hint" style="color: #64748b;">Nama lengkap beserta gelar akademik jika ada.</span>
                </div>

                <!-- NIP -->
                <div>
                    <label for="nip" class="pd-label" style="color: #0f172a; font-weight: 700;">NIP (Nomor Induk Pegawai)</label>
                    <input type="text" inputmode="numeric" maxlength="18" name="nip" id="nip" value="{{ old('nip', $guru->nip) }}" placeholder="18 digit angka (khusus PNS/PPPK)" class="pd-input" style="font-family: monospace; letter-spacing: 0.05em; background: #ffffff; color: #0f172a; border-color: #cbd5e1;">
                    <span class="pd-hint" style="color: #64748b;">Tepat 18 digit angka tanpa spasi. Kosongkan jika Non-PNS.</span>
                </div>

                <!-- NIK -->
                <div>
                    <label for="nik" class="pd-label" style="color: #0f172a; font-weight: 700;">NIK (Nomor Induk Kependudukan)</label>
                    <input type="text" inputmode="numeric" maxlength="16" name="nik" id="nik" value="{{ old('nik', $guru->nik) }}" placeholder="16 digit angka sesuai KTP/KK" class="pd-input" style="font-family: monospace; letter-spacing: 0.05em; background: #ffffff; color: #0f172a; border-color: #cbd5e1;">
                    <span class="pd-hint" style="color: #64748b;">Tepat 16 digit angka sesuai dokumen KTP/KK resmi.</span>
                </div>
            </div>
        </div>

        <!-- 2. PANGKAT, GOLONGAN & KATEGORI TPG -->
        <div>
            <div class="pub-step-header">
                <span class="pub-step-num">2</span>
                <div>
                    <h3 class="pub-step-title" style="color: #0f172a;">Pangkat, Golongan & Kategori TPG</h3>
                    <small class="pub-step-desc" style="color: #64748b;">Pangkat kepegawaian dan kategori tunjangan profesi guru</small>
                </div>
            </div>

            <div class="pd-form-grid-2">
                <!-- Pangkat & Golongan -->
                <div>
                    <label for="pangkat_golongan_id" class="pd-label" style="color: #0f172a; font-weight: 700;">Pangkat / Golongan Ruang <em>*</em></label>
                    <select name="pangkat_golongan_id" id="pangkat_golongan_id" required class="pd-select-field" style="background: #ffffff; color: #0f172a; border-color: #cbd5e1;">
                        <option value="">-- Pilih Pangkat & Golongan --</option>
                        @foreach($pangkats as $pangkat)
                            <option value="{{ $pangkat->id }}" {{ old('pangkat_golongan_id', $guru->pangkat_golongan_id) == $pangkat->id ? 'selected' : '' }}>
                                {{ $pangkat->golongan }} — {{ $pangkat->pangkat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kategori TPG -->
                <div>
                    <label for="penerima_tpg" class="pd-label" style="color: #0f172a; font-weight: 700;">Kategori Penerima TPG</label>
                    <select name="penerima_tpg" id="penerima_tpg" class="pd-select-field" style="background: #ffffff; color: #0f172a; border-color: #cbd5e1;">
                        <option value="">-- Bukan Penerima / Belum Sertifikasi --</option>
                        @foreach($tpgChoices as $tpg)
                            <option value="{{ $tpg }}" {{ old('penerima_tpg', $guru->penerima_tpg) == $tpg ? 'selected' : '' }}>
                                {{ $tpg }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 3. GAJI POKOK, KGB & PENSIUN -->
        <div>
            <div class="pub-step-header">
                <span class="pub-step-num">3</span>
                <div>
                    <h3 class="pub-step-title" style="color: #0f172a;">Gaji Pokok, KGB & Pensiun</h3>
                    <small class="pub-step-desc" style="color: #64748b;">Informasi besaran gaji pokok serta perkiraan waktu KGB dan pensiun</small>
                </div>
            </div>

            <div class="pd-form-grid-3">
                <!-- Gaji Pokok -->
                <div>
                    <label for="gaji_pokok" class="pd-label" style="color: #0f172a; font-weight: 700;">Gaji Pokok Saat Ini (Rp) <em>*</em></label>
                    <input type="number" step="0.01" min="0" name="gaji_pokok" id="gaji_pokok" value="{{ old('gaji_pokok', $guru->gaji_pokok) }}" required placeholder="Contoh: 3500000" class="pd-input" style="font-family: monospace; font-weight: 700; background: #ffffff; color: #0f172a; border-color: #cbd5e1;">
                    <span class="pd-hint" style="color: #64748b;">Masukkan angka murni tanpa titik atau koma.</span>
                </div>

                <!-- KGB Berikutnya -->
                <div>
                    <label class="pd-label" style="color: #0f172a; font-weight: 700;">Berkala Berikutnya (KGB) <em>*</em></label>
                    <div style="display: grid; grid-template-columns: 1.3fr 1fr; gap: 8px;">
                        <select name="kgb_bulan" id="kgb_bulan" required class="pd-select-field" style="background: #ffffff; color: #0f172a; border-color: #cbd5e1;">
                            <option value="">Bulan</option>
                            @foreach($bulanChoices as $num => $namaBulan)
                                <option value="{{ $num }}" {{ old('kgb_bulan', $guru->kgb_bulan) == $num ? 'selected' : '' }}>{{ $namaBulan }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="kgb_tahun" id="kgb_tahun" min="1900" max="2100" placeholder="Tahun" value="{{ old('kgb_tahun', $guru->kgb_tahun) }}" required class="pd-input" style="background: #ffffff; color: #0f172a; border-color: #cbd5e1;">
                    </div>
                    <span class="pd-hint" style="color: #64748b;">Bulan & tahun kenaikan berkala.</span>
                </div>

                <!-- Batas Usia Pensiun -->
                <div>
                    <label class="pd-label" style="color: #0f172a; font-weight: 700;">Perkiraan Pensiun <em>*</em></label>
                    <div style="display: grid; grid-template-columns: 1.3fr 1fr; gap: 8px;">
                        <select name="pensiun_bulan" id="pensiun_bulan" required class="pd-select-field" style="background: #ffffff; color: #0f172a; border-color: #cbd5e1;">
                            <option value="">Bulan</option>
                            @foreach($bulanChoices as $num => $namaBulan)
                                <option value="{{ $num }}" {{ old('pensiun_bulan', $guru->pensiun_bulan) == $num ? 'selected' : '' }}>{{ $namaBulan }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="pensiun_tahun" id="pensiun_tahun" min="1900" max="2100" placeholder="Tahun" value="{{ old('pensiun_tahun', $guru->pensiun_tahun) }}" required class="pd-input" style="background: #ffffff; color: #0f172a; border-color: #cbd5e1;">
                    </div>
                    <span class="pd-hint" style="color: #64748b;">Bulan & perkiraan tahun pensiun.</span>
                </div>
            </div>
        </div>

        <!-- 4. SATUAN PENDIDIKAN / SEKOLAH -->
        <div>
            <div class="pub-step-header">
                <span class="pub-step-num">4</span>
                <div>
                    <h3 class="pub-step-title" style="color: #0f172a;">Satuan Pendidikan / Tempat Tugas</h3>
                    <small class="pub-step-desc" style="color: #64748b;">Pilih sekolah dari Master Sekolah resmi BIMKAT</small>
                </div>
            </div>

            <!-- Searchable Dropdown Sekolah -->
            <div x-data="schoolSearch('{{ old('sekolah_nama', $guru->sekolah ? $guru->sekolah->nama_sekolah : '') }}', '{{ old('sekolah_id', $guru->sekolah_id) }}')" style="position: relative;">
                <label for="sekolah_search" class="pd-label" style="color: #0f172a; font-weight: 700;">Cari & Pilih Sekolah Tempat Tugas <em>*</em></label>
                
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
                           style="padding-right: 40px; background: #ffffff; color: #0f172a; border-color: #cbd5e1;"
                           autocomplete="off">

                    <!-- Status Indicator -->
                    <div style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%);">
                        <span x-show="loading" style="color: #ea580c; font-size: 13px; display: none;">⏳</span>
                        <button type="button" x-show="query && !loading" @click="clear()" style="background: transparent; border: 0; color: #64748b; cursor: pointer; font-size: 18px; display: none;">×</button>
                    </div>
                </div>

                <!-- Dropdown Hasil Pencarian -->
                <div x-show="open && results.length > 0"
                     @click.away="open = false"
                     class="pub-search-dropdown"
                     style="display: none; background: #ffffff; border: 1px solid #cbd5e1;">
                    <template x-for="item in results" :key="item.id_sekolah">
                        <div @click="choose(item)" class="pub-search-item">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
                                <strong x-text="item.nama_sekolah" style="color: #0f172a;"></strong>
                                <span class="pub-badge-year" style="font-size: 10px; padding: 2px 8px; flex-shrink: 0; background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa;" x-text="item.jenjang || 'Sekolah'"></span>
                            </div>
                            <div style="font-size: 11px; margin-top: 4px; display: flex; flex-wrap: wrap; gap: 10px; color: #64748b;">
                                <span x-show="item.npsn">NPSN: <b x-text="item.npsn" style="color: #ea580c;"></b></span>
                                <span x-show="item.kabupaten" x-text="item.kabupaten"></span>
                                <span x-show="item.provinsi" x-text="'(' + item.provinsi + ')'"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Info Box If Selected -->
                <div x-show="selectedId" class="pub-selected-school" style="display: flex; justify-content: space-between; align-items: center; background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 16px; border-radius: 10px; margin-top: 8px;">
                    <div>
                        <span style="font-weight: 700;">✓ Sekolah Terpilih:</span>
                        <strong style="margin-left: 4px;" x-text="query"></strong>
                    </div>
                    <button type="button" @click="clear()" style="background: transparent; border: 0; color: #dc2626; font-size: 12px; font-weight: 700; cursor: pointer;">Ganti</button>
                </div>

                <span class="pd-hint" style="color: #64748b;">Sekolah tersinkronisasi langsung dari database BIMKAT.</span>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div style="display: flex; flex-direction: column; gap: 14px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
            <div style="font-size: 12.5px; color: #475569; text-align: center;">
                Periksa kembali data Anda sebelum menekan tombol simpan di bawah.
            </div>

            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('guru.cek_status') }}" class="pd-button pd-button-secondary" style="flex: 1; min-height: 48px; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    <span>← Batal & Kembali</span>
                </a>
                <button type="submit" class="pub-button pub-button-primary pub-button-large" style="flex: 2; min-height: 48px; font-size: 14px; font-weight: 800;">
                    <span>SIMPAN PERBAIKAN DATA SEKARANG</span>
                    <span>→</span>
                </button>
            </div>
        </div>
    </form>

</div>
</section>
@endsection
