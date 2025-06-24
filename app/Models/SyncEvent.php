<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncEvent extends Model
{
    protected $fillable = [
        'video_session_id',
        'event_type',
        'timestamp',
        'playback_rate',
        'user_id',
        'user_name',
        'event_data',
        'client_id',
        'ip_address',
        'user_agent',
        'occurred_at',
    ];

    protected $casts = [
        'event_data' => 'array',
        'timestamp' => 'decimal:3',
        'playback_rate' => 'decimal:2',
        'occurred_at' => 'datetime',
    ];

    public function videoSession(): BelongsTo
    {
        return $this->belongsTo(VideoSession::class);
    }

    public function getEventTypeColorAttribute(): string
    {
        return match ($this->event_type) {
            'play' => 'success',
            'pause' => 'warning',
            'seek' => 'info',
            'speed_change' => 'info',
            'buffer' => 'warning',
            'error' => 'danger',
            'viewer_join' => 'success',
            'viewer_leave' => 'gray',
            default => 'gray',
        };
    }

    public function getFormattedTimestampAttribute(): string
    {
        $seconds = (float) $this->timestamp;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%06.3f', $hours, $minutes, $seconds);
        } else {
            return sprintf('%02d:%06.3f', $minutes, $seconds);
        }
    }
}
