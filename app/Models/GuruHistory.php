<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruHistory extends Model
{
    protected $table = 'guru_histories';

    protected $fillable = [
        'guru_id',
        'user_id',
        'action',
        'catatan',
        'old_values',
        'new_values',
        'ip_address',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
