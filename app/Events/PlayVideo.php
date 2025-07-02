<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;   // <-- add
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayVideo implements ShouldBroadcastNow               // <-- implement
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $videoId,
        public float  $startTime
    ) {}

    // public channel - everyone in the watch-party hears it
    public function broadcastOn(): Channel
    {
        return new Channel('video-sync');
    }

    // the JS side subscribes to '.PlayVideo'
    public function broadcastAs(): string
    {
        return 'PlayVideo';
    }

    // payload Echo receives
    public function broadcastWith(): array
    {
        return [
            'videoId'   => $this->videoId,
            'startTime' => $this->startTime,
        ];
    }
}
