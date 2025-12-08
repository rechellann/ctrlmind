<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;

class ConsultantController extends Controller
{
    public function dashboard()
    {
        $clients = auth()->user()->clients; // assumes relation defined in User model
        return view('consultants.dashboard', compact('clients'));
    }

    public function chat(User $user)
    {
        $consultant = auth()->user();

        $messages = Message::where(function ($query) use ($user, $consultant) {
                $query->where('user_id', $user->id)
                    ->where('consultant_id', $consultant->id);
            })
            ->orWhere(function ($query) use ($user, $consultant) {
                $query->where('user_id', $consultant->id)
                    ->where('consultant_id', $user->id);
            })
            ->orderBy('created_at')
            ->get();

        return view('consultants.chat', compact('messages', 'user'));
    }

    public function reply(Request $request, User $user)
    {
        $consultant = auth()->user();

        Message::create([
            'sender_id'   => $consultant->id,
            'receiver_id' => $user->id,
            'content'     => $request->content,
        ]);

        return redirect()->back()->with('success', 'Message sent.');
    }
    
    public function notifications()
    {
        // TODO: fetch unread messages or journal shares
        return view('consultants.notifications');
    }

    public function sharedJournals()
    {
        // TODO: fetch shared journals from a pivot table
        return view('consultants.shared-journals');
    }
}
