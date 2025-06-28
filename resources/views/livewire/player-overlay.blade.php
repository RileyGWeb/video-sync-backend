<div 
    x-data
    x-on:log.window="console.log('Received log event:', $event.detail)"
>
    {{-- Because she competes with no one, no one can compete with her. --}}

    <script src="https://extension-files.twitch.tv/helper/v1/twitch-ext.min.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>

    <script>
        let player;

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                height: '100%',
                width: '100%',
                videoId: 'dQw4w9WgXcQ', // Replace this with a test video ID
                events: {
                    'onReady': (event) => {
                        console.log('YT Player ready');
                    },
                    'onStateChange': (event) => {
                        console.log('YT Player state changed:', event.data);
                    }
                }
            });
        }

        window.onYouTubeIframeAPIReady = onYouTubeIframeAPIReady;

        window.addEventListener('livewire:load', () => {
            Livewire.on('syncVideo', () => {
                console.log('Livewire → Sync clicked');
                if (player) player.seekTo(0);
            });

            Livewire.on('skipVote', () => {
                console.log('Livewire → Skip Vote clicked');
                if (player) player.seekTo(player.getCurrentTime() + 10);
            });
        });
    </script>

    <style>
        #player {
            width: 100%;
            height: 360px;
            background: black;
        }
    </style>


    <div id="player"></div>

    <button wire:click="syncVideo">Sync</button>
    <button wire:click="skipVote">Skip Vote</button>
</div>
