<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MouseMovement;
use Illuminate\Support\Facades\Auth;

class MouseMovementController extends Controller
{
    public function saveMovements(Request $request)
    {
        $mouseMovement = new MouseMovement();
        $mouseMovement->user_id = Auth::user()->id;
        $mouseMovement->name = $request->name;
        $mouseMovement->movements = json_encode($request->movements);
        $mouseMovement->save();

        return response()->json(['success' => true, 'id' => $mouseMovement->id]);
    }

    public function replay($id)
    {
        $mouseMovement = MouseMovement::findOrFail($id);
        return view('replay', ['movements' => $mouseMovement->movements, 'replayInfo' => $mouseMovement]);
    }

    public function userReplays()
    {
        $user = Auth::user();

        // Fetch all replays made by the authenticated user
        $replays = MouseMovement::where('user_id', $user->id)->get();

        return view('replay-list', compact('replays'));
    }

    public function deleteReplay($id)
    {
        $user = Auth::user();

        // Find the replay and ensure it belongs to the authenticated user
        $replay = MouseMovement::where('user_id', $user->id)->where('id', $id)->firstOrFail();

        // Delete the replay
        $replay->delete();

        return redirect()->route('replay.get')->with('success', 'Replay deleted successfully.');
    }
    
    public function store()
    {
        $user = Auth::user();

        // Check if a replay with the same name already exists for this user
        $existingReplay = MouseMovement::where('user_id', $user->id)
                                       ->where('name', $request->name)
                                       ->first();

                                       if ($existingReplay) {
                                        return redirect()->back()->withErrors(['name' => 'Sorry, a drawing with this name already exists!'])->withInput();
                                    }

        // Store the replay if no duplicates are found
        $mouseMovement = new MouseMovement();
        $mouseMovement->user_id = $user->id;
        $mouseMovement->name = $request->name;
        $mouseMovement->movements = $request->movements;
        $mouseMovement->save();

        return redirect()->route('replay.get')->with('success', 'Replay saved successfully.');
    }
    
}

