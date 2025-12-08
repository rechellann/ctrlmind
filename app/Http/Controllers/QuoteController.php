<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\UserQuote;
use App\Models\Resource; 
use Illuminate\Support\Facades\Cache;

class QuoteController extends Controller
{
    // Show all quotes saved by the current user
    public function index()
    {
        $userQuotes = UserQuote::where('user_id', auth()->id())
                            ->with('quote')
                            ->orderByDesc('pinned')   // pinned first
                            ->orderBy('created_at', 'desc') // then newest saved
                            ->get();

        return view('quotes.index', compact('userQuotes'));
    }

    // Save/unsave toggle
    public function toggle(Request $request)
    {
        $userId = auth()->id();
        $quoteId = $request->quote_id;

        $quote = Quote::find($quoteId);
        if (!$quote) {
            return redirect()->back()->with('error', 'Quote not found.');
        }

        $existing = UserQuote::where('user_id', $userId)
                            ->where('quote_id', $quoteId)
                            ->first();

        if ($existing) {
            $existing->delete(); // unsave
        } else {
            UserQuote::create([
                'user_id' => $userId,
                'quote_id' => $quoteId,
                'pinned' => false,
            ]);
        }

        return redirect()->back();
    }

    // Admin uploads a new quote
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'author' => 'nullable|string',
        ]);

        Quote::create([
            'text' => $request->text,
            'author' => $request->author,
        ]);

        return redirect()->back()->with('success', 'Quote added!');
    }

    // Pin/unpin quotes (max 3 per user)
    public function pin($quoteId)
    {
        $userId = auth()->id();

        $userQuote = UserQuote::where('user_id', $userId)
                            ->where('quote_id', $quoteId)
                            ->firstOrFail();

        if (!$userQuote->pinned) {
            $pinnedCount = UserQuote::where('user_id', $userId)
                                    ->where('pinned', true)
                                    ->count();
            if ($pinnedCount >= 3) {
                return redirect()->back()->with('error', 'You can only pin 3 quotes.');
            }
        }

        $userQuote->update(['pinned' => !$userQuote->pinned]);

        return redirect()->back()->with('success', $userQuote->pinned ? 'Pinned!' : 'Unpinned!');
    }

    // Dashboard
    public function dashboard()
    {
        $today = now()->toDateString();

        // Cache one random quote for the whole day
        $quote = Cache::remember("quote_of_the_day_{$today}", now()->addDay(), function () {
            return Quote::inRandomOrder()->first();
        });

        $savedQuoteIds = UserQuote::where('user_id', auth()->id())->pluck('quote_id');
        $featuredResources = Resource::where('is_featured', true)->take(3)->get();

        return view('dashboard', compact('quote', 'savedQuoteIds', 'featuredResources'));
    }

    // Redirect to journal creation with quote
    public function redirectToJournal(Request $request)
    {
        $quoteId = $request->quote_id;
        return redirect()->route('journal.create', ['quote_id' => $quoteId]);
    }
}