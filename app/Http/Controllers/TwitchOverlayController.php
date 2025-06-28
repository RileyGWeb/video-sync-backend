<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TwitchOverlayController extends Controller
{
    public function sync(Request $request)
    {
        $twitchUser = $request->input('twitch_user');
        $role = $twitchUser['role'] ?? null;

        if ($role !== 'broadcaster') {
            return response()->json(['error' => 'Only broadcaster can sync'], 403);
        }

        // Do your sync logic here (e.g., emit a Reverb event)
        return response()->json(['status' => 'sync accepted']);
    }
}
