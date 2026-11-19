<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sekolah extends Model
{
    use SoftDeletes;

    protected $table = 'sekolah';
    protected $primaryKey = 'id_sekolah';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id_sekolah',
        'nama_sekolah',
        'npsn',
        'jenjang',
        'status_sekolah',
        'alamat_sekolah',
        'kabupaten',
        'provinsi',
        'id_kabupaten',
        'id_kecamatan',
    ];

    public function gurus()
    {
        return $this->hasMany(Guru::class, 'sekolah_id', 'id_sekolah');
    }
}
