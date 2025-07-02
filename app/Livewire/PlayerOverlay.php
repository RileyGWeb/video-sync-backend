<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Events\PlayVideo;

#[Layout('layouts.overlay')]
class PlayerOverlay extends Component
{
    public function syncVideo(string $videoId, float $startTime)
    {
        broadcast(new PlayVideo($videoId, $startTime));   // <-- no ->toOthers()
        $this->dispatch('log', message: 'Sync broadcasted!');
    }

    public function skipVote()
    {
        $this->dispatch('log', message: 'Skip Vote clicked!');
        // more logic later…
    }

    public function render()
    {
        return view('livewire.player-overlay');
    }
}
