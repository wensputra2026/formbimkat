<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class GuruUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $guruId = $this->route('guru') ? (is_object($this->route('guru')) ? $this->route('guru')->id : $this->route('guru')) : null;

        return [
            'nama' => ['required', 'string', 'max:150'],
            'nip' => ['nullable', 'digits:18', Rule::unique('gurus', 'nip')->ignore($guruId)],
            'nik' => ['nullable', 'digits:16', Rule::unique('gurus', 'nik')->ignore($guruId)],
            'pangkat_golongan_id' => ['required', 'exists:pangkat_golongans,id'],
            'gaji_pokok' => ['required', 'numeric', 'min:0'],
            'kgb_bulan' => ['required', 'integer', 'between:1,12'],
            'kgb_tahun' => ['required', 'integer', 'digits:4', 'min:1900', 'max:2100'],
            'pensiun_bulan' => ['required', 'integer', 'between:1,12'],
            'pensiun_tahun' => ['required', 'integer', 'digits:4', 'min:1900', 'max:2100'],
            'penerima_tpg' => ['nullable', 'in:Kemenag,Kemenag 2026,Dinas,Dinas 2026,PPPK,PPPK 2026'],
            'sekolah_id' => ['required', 'exists:sekolah,id_sekolah'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $nip = trim((string)$this->input('nip'));
                $nik = trim((string)$this->input('nik'));

                if (empty($nip) && empty($nik)) {
                    $validator->errors()->add('nip', 'Minimal salah satu dari NIP atau NIK wajib diisi.');
                    $validator->errors()->add('nik', 'Minimal salah satu dari NIP atau NIK wajib diisi.');
                }
            }
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nip.digits' => 'NIP harus tepat 18 digit angka.',
            'nip.unique' => 'NIP sudah digunakan oleh data lain.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'nik.unique' => 'NIK sudah digunakan oleh data lain.',
            'pangkat_golongan_id.required' => 'Pangkat/Golongan wajib dipilih.',
            'gaji_pokok.required' => 'Gaji pokok saat ini wajib diisi.',
            'gaji_pokok.numeric' => 'Gaji pokok harus berupa angka.',
            'gaji_pokok.min' => 'Gaji pokok tidak boleh bernilai negatif.',
            'kgb_bulan.required' => 'Bulan berkala berikutnya wajib dipilih.',
            'kgb_tahun.required' => 'Tahun berkala berikutnya wajib diisi.',
            'pensiun_bulan.required' => 'Bulan pensiun wajib dipilih.',
            'pensiun_tahun.required' => 'Tahun pensiun wajib diisi.',
            'sekolah_id.required' => 'Sekolah wajib dipilih.',
            'sekolah_id.exists' => 'Sekolah yang dipilih tidak valid.',
            'penerima_tpg.in' => 'Pilihan Penerima TPG tidak valid.',
        ];
    }
}
