<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Guru extends Model
{
    use SoftDeletes;

    protected $table = 'gurus';

    protected $fillable = [
        'nomor_registrasi',
        'nama',
        'nip',
        'nik',
        'pangkat_golongan_id',
        'gaji_pokok',
        'kgb_bulan',
        'kgb_tahun',
        'pensiun_bulan',
        'pensiun_tahun',
        'penerima_tpg',
        'sekolah_id',
        'status_verifikasi',
        'diverifikasi_oleh',
        'diverifikasi_pada',
        'alasan_penolakan',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'kgb_bulan' => 'integer',
        'kgb_tahun' => 'integer',
        'pensiun_bulan' => 'integer',
        'pensiun_tahun' => 'integer',
        'diverifikasi_pada' => 'datetime',
    ];

    public const BULAN = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public const TPG_CHOICES = [
        'Kemenag',
        'Kemenag 2026',
        'Dinas',
        'Dinas 2026',
        'PPPK',
        'PPPK 2026',
    ];

    public const STATUS_CHOICES = [
        'Menunggu Verifikasi',
        'Disetujui',
        'Ditolak',
    ];

    public static function generateNomorRegistrasi(): string
    {
        $year = date('Y');
        do {
            $code = 'PGK-' . $year . '-' . strtoupper(Str::random(8));
        } while (static::where('nomor_registrasi', $code)->exists());

        return $code;
    }

    public function pangkatGolongan()
    {
        return $this->belongsTo(PangkatGolongan::class, 'pangkat_golongan_id');
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'id_sekolah');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function histories()
    {
        return $this->hasMany(GuruHistory::class, 'guru_id')->latest();
    }

    public function getFormattedGajiAttribute(): string
    {
        return 'Rp ' . number_format((float)$this->gaji_pokok, 2, ',', '.');
    }

    public function getKgbFormattedAttribute(): string
    {
        if (!$this->kgb_bulan || !$this->kgb_tahun) {
            return '-';
        }
        $bulan = self::BULAN[$this->kgb_bulan] ?? $this->kgb_bulan;
        return "{$bulan} {$this->kgb_tahun}";
    }

    public function getPensiunFormattedAttribute(): string
    {
        if (!$this->pensiun_bulan || !$this->pensiun_tahun) {
            return '-';
        }
        $bulan = self::BULAN[$this->pensiun_bulan] ?? $this->pensiun_bulan;
        return "{$bulan} {$this->pensiun_tahun}";
    }

    public function scopeMenungguVerifikasi($query)
    {
        return $query->where('status_verifikasi', 'Menunggu Verifikasi');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status_verifikasi', 'Disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status_verifikasi', 'Ditolak');
    }
}
