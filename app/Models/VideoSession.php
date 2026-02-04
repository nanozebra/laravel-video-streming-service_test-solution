<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VideoSession extends Model
{
    use HasFactory;
    protected $fillable = [
        'video_id',
        'session_token',
        'ip_address',
        'user_agent',
        'expires_at',
        'max_plays',
        'plays_count',
        'is_active'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public static function createSession($videoId, $request = null)
    {
        $request = $request ?: request();
        return self::create([
            'video_id' => $videoId,
            'session_token' => Str::uuid(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'expires_at' => now()->addMinutes(30),
            'max_plays' => 3,
            'plays_count' => 0,
            'is_active' => true
        ]);
    }

    public function isValid()
    {
        return $this->is_active && 
               $this->expires_at->isFuture() && 
               $this->plays_count < $this->max_plays;
    }
}