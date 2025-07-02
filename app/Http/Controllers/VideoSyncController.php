<?php

namespace App\Http\Controllers;

use App\Events\PlayVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VideoSyncController extends Controller
{
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'videoId' => 'required|string',
            'startTime' => 'required|numeric',
        ]);

        Log::info('Broadcasting PlayVideo event from API', $validated);

        broadcast(new PlayVideo($validated['videoId'], $validated['startTime']))->toOthers();

        return response()->json(['status' => 'event broadcasted']);
    }
}
