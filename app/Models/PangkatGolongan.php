<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PangkatGolongan extends Model
{
    protected $table = 'pangkat_golongans';

    protected $fillable = [
        'golongan',
        'pangkat',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    public function getLabelAttribute(): string
    {
        return "{$this->golongan} - {$this->pangkat}";
    }

    public function gurus()
    {
        return $this->hasMany(Guru::class);
    }
}
