<div
    x-data="{
        player: null,
        currentVideoId: 'M7lc1UVf-VE',
        csrfToken: '{{ csrf_token() }}',

        initPlayer() {
            this.player = new YT.Player('player', {
                height: '100%',
                width: '100%',
                videoId: this.currentVideoId,
                playerVars: { origin: location.origin, rel: 0, playsinline: 1 },
                events: { 'onReady': () => console.log('YT Player ready') }
            });
        },

        updatePlayer(videoId, startTime) {
            console.log(`Updating player to ${videoId} at ${startTime}s`);
            if (this.player && typeof this.player.loadVideoById === 'function') {
                this.currentVideoId = videoId;
                this.player.loadVideoById({ videoId: videoId, startSeconds: startTime });
            } else {
                console.error('Player not initialized or invalid for update.');
            }
        },

        sync() {
            if (!this.player) return console.error('Player not ready for sync');
            const currentTime = this.player.getCurrentTime();
            const videoId = this.currentVideoId;
            console.log(`Sending sync request for ${videoId} at ${currentTime}s`);

            fetch('/api/sync-video', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({ videoId: videoId, startTime: currentTime })
            })
            .then(response => response.json())
            .then(data => console.log('Sync response:', data));
        }
    }"
    x-init="() => {
        // — YT player bootstrap —
        window.onYouTubeIframeAPIReady = () => {
            initPlayer();
        };

        // — Echo wiring —
        const attachEchoListener = () => {
            if (!window.Echo) {
                console.warn('Echo not ready, listening for echo:ready');
                window.addEventListener('echo:ready', attachEchoListener, { once: true });
                return;
            }

            console.log('Attaching Echo listener');
            window.Echo.channel('video-sync')
                .listen('.PlayVideo', e => {
                    console.log('PlayVideo event received', e);
                    updatePlayer(e.videoId, e.startTime);
                });
        }

        // Initial call in case Echo is already ready
        attachEchoListener();

        // Load YT API if not already loaded
        if (!window.YT) {
            var tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        } else if (typeof onYouTubeIframeAPIReady !== 'undefined') {
            onYouTubeIframeAPIReady();
        }
    }"
    wire:key="player-overlay"
    data-id="player-overlay-component"
>
    <script src="https://extension-files.twitch.tv/helper/v1/twitch-ext.min.js"></script>
    
    <style>
        #player { width:100%; height:360px; background:black; }
    </style>

    <div id="player" wire:ignore></div>

    <button x-on:click="sync()">Sync</button>
    <button wire:click="skipVote">Skip Vote</button>
</div>
