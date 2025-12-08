<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
    public function index()
{
    $consultant = User::find(auth()->user()->consultant_id);

        // Fetch messages between the logged-in user and their consultant
        $messages = Message::where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('consultant_id', auth()->id())
                    ->orWhere('receiver_id', auth()->id());
            })
            ->orderBy('created_at')
            ->get();

        // If no messages yet, send a welcome message from consultant
        if ($messages->isEmpty() && $consultant) {
            Message::create([
                'user_id'       => $consultant->id,   // consultant is sender
                'consultant_id' => auth()->id(),      // logged-in user is receiver
                'receiver_id'   => auth()->id(),      // explicitly set receiver
                'content'       => 'Hi! I’m here if you need anything. How can I help today?',
            ]);

            $messages = Message::where(function ($query) {
                    $query->where('user_id', auth()->id())
                        ->orWhere('consultant_id', auth()->id())
                        ->orWhere('receiver_id', auth()->id());
                })
                ->orderBy('created_at')
                ->get();
        }

        return view('chat.index', compact('messages', 'consultant'));
    }
    
    public function store(Request $request)
    {
        $consultantId = auth()->user()->consultant_id;

        Message::create([
            'user_id'       => auth()->id(),     // logged-in user
            'consultant_id' => $consultantId,    // consultant assigned
            'receiver_id'   => $consultantId,    // consultant receives
            'content'       => $request->content,
        ]);

        return redirect()->back();
    }


}
