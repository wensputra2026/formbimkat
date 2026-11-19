<?php

namespace App\Http\Requests;

use App\Models\Guru;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuruRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'nip' => ['nullable', 'required_without:nik', 'digits:18', 'unique:gurus,nip'],
            'nik' => ['nullable', 'required_without:nip', 'digits:16', 'unique:gurus,nik'],
            'pangkat_golongan_id' => ['required', 'exists:pangkat_golongans,id'],
            'gaji_pokok' => ['required', 'numeric', 'min:0'],
            'kgb_bulan' => ['required', 'integer', 'between:1,12'],
            'kgb_tahun' => ['required', 'integer', 'digits:4', 'min:1900', 'max:2100'],
            'pensiun_bulan' => ['required', 'integer', 'between:1,12'],
            'pensiun_tahun' => ['required', 'integer', 'digits:4', 'min:1900', 'max:2100'],
            'penerima_tpg' => ['nullable', Rule::in(Guru::getTpgChoices())],
            'sekolah_id' => ['required', 'exists:sekolah,id_sekolah'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nip.required_without' => 'Minimal salah satu dari NIP atau NIK wajib diisi.',
            'nip.digits' => 'NIP harus tepat 18 digit angka.',
            'nip.unique' => 'NIP sudah terdaftar dalam sistem.',
            'nik.required_without' => 'Minimal salah satu dari NIP atau NIK wajib diisi.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar dalam sistem.',
            'pangkat_golongan_id.required' => 'Pangkat/Golongan wajib dipilih.',
            'gaji_pokok.required' => 'Gaji pokok saat ini wajib diisi.',
            'gaji_pokok.numeric' => 'Gaji pokok harus berupa angka.',
            'gaji_pokok.min' => 'Gaji pokok tidak boleh bernilai negatif.',
            'kgb_bulan.required' => 'Bulan berkala berikutnya wajib dipilih.',
            'kgb_tahun.required' => 'Tahun berkala berikutnya wajib diisi.',
            'kgb_tahun.digits' => 'Tahun berkala berikutnya harus 4 digit angka.',
            'pensiun_bulan.required' => 'Bulan pensiun wajib dipilih.',
            'pensiun_tahun.required' => 'Tahun pensiun wajib diisi.',
            'pensiun_tahun.digits' => 'Tahun pensiun harus 4 digit angka.',
            'sekolah_id.required' => 'Sekolah wajib dipilih dari daftar pencarian.',
            'sekolah_id.exists' => 'Sekolah yang dipilih tidak valid.',
            'penerima_tpg.in' => 'Pilihan Penerima TPG tidak valid.',
        ];
    }
}
