<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = __('Chat');
        return view('site.chat.index', compact('title'));
    }

    public function send(Request $request)
    {
        $name = current_user()?->name ?? __('Guest');
        event(new MessageSent(
            $request->message,
            $name
            // $request->user
        ));

        return response()->json([
            'status' => 'sent',
            'data' => [
                'user' => $name,
                'message' => $request->message,
            ]
        ]);
    }
}
