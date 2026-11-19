<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $fillable = [
        'username',
        'user_id',
        'ip_address',
        'user_agent',
        'browser',
        'status',
        'failure_reason',
        'login_at',
        'logout_at',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function detectBrowser(?string $userAgent): string
    {
        if (!$userAgent) return 'Unknown';
        if (str_contains($userAgent, 'Edg')) return 'Microsoft Edge';
        if (str_contains($userAgent, 'Chrome')) return 'Google Chrome';
        if (str_contains($userAgent, 'Firefox')) return 'Mozilla Firefox';
        if (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) return 'Apple Safari';
        if (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) return 'Opera';
        return 'Other Browser';
    }
}
