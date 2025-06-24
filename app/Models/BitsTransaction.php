<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BitsTransaction extends Model
{
    protected $fillable = [
        'video_session_id',
        'transaction_id',
        'user_id',
        'user_name',
        'user_login',
        'bits_used',
        'total_bits_used',
        'message',
        'is_anonymous',
        'context',
        'product_type',
        'product_sku',
        'video_timestamp',
        'twitch_timestamp',
        'raw_data',
    ];

    protected $casts = [
        'context' => 'array',
        'raw_data' => 'array',
        'bits_used' => 'integer',
        'total_bits_used' => 'decimal:0',
        'video_timestamp' => 'decimal:3',
        'is_anonymous' => 'boolean',
        'twitch_timestamp' => 'datetime',
    ];

    public function videoSession(): BelongsTo
    {
        return $this->belongsTo(VideoSession::class);
    }

    public function getBitsValueAttribute(): float
    {
        // Bits are typically worth $0.01 USD per bit
        return $this->bits_used * 0.01;
    }

    public function getFormattedBitsAttribute(): string
    {
        return number_format($this->bits_used) . ' bits';
    }

    public function getFormattedValueAttribute(): string
    {
        return '$' . number_format($this->bits_value, 2);
    }

    public function getFormattedVideoTimestampAttribute(): ?string
    {
        if (!$this->video_timestamp) {
            return null;
        }

        $seconds = (float) $this->video_timestamp;
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
