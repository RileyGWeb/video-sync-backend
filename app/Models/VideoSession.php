<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoSession extends Model
{
    protected $fillable = [
        'session_id',
        'title',
        'description',
        'platform',
        'platform_video_id',
        'streamer_name',
        'streamer_id',
        'status',
        'viewer_count',
        'current_timestamp',
        'is_live',
        'started_at',
        'ended_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'current_timestamp' => 'decimal:3',
        'is_live' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function syncEvents(): HasMany
    {
        return $this->hasMany(SyncEvent::class);
    }

    public function bitsTransactions(): HasMany
    {
        return $this->hasMany(BitsTransaction::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'success',
            'paused' => 'warning',
            'ended' => 'gray',
            'error' => 'danger',
            default => 'gray',
        };
    }

    public function getDurationAttribute(): ?string
    {
        if (!$this->started_at) {
            return null;
        }

        $endTime = $this->ended_at ?? now();
        $duration = $this->started_at->diffInSeconds($endTime);

        return gmdate('H:i:s', $duration);
    }
}
