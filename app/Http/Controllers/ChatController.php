<?php

namespace App\Http\Controllers;

use App\User;
use App\Events\ChatEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chat()
    {
        $user = Auth::user();
        return view('chat', compact('user'));
    }

    public function send(Request $request)
    {
        $user = Auth::user();
        $this->saveToSession($request);
        event(new ChatEvent($request->input('message'), $user));
    }

    public function saveToSession(Request $request)
    {
        session()->put('chat', $request->chat);
    }

    public function getOldMessages()
    {
        return session('chat');
    }

    public function deleteSession()
    {
        session()->forget('chat');
    }
}
