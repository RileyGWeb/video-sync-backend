<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.overlay')]
class PlayerOverlay extends Component
{
    public function syncVideo()
    {
        $this->dispatch('log', 'syncing video!');
        $this->dispatch('syncVideo');
    }

    public function skipVote()
    {
        $this->dispatch('log', 'skipping vote!');
        $this->dispatch('skipVote');
    }

    public function render()
    {
        return view('livewire.player-overlay');
    }
}
