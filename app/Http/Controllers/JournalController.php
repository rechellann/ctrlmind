<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\Quote;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    // Show the "create journal entry" form with a quote (optional)
    public function create(Request $request)
    {
        $quoteId = $request->quote_id;
        $quote = Quote::find($quoteId);

        return view('journal.create', compact('quote'));
    }

    // Store a journal entry (either free-form or tied to a quote)
    public function store(Request $request)
    {
        $request->validate([
            'reflection' => 'required|string|max:500',
            'quote_id'   => 'nullable|exists:quotes,id',
        ]);

        JournalEntry::create([
            'user_id'    => auth()->id(),
            'quote_id'   => $request->quote_id, // can be null
            'reflection' => $request->reflection,
        ]);

        return redirect()->route('journal.index')->with('success', 'Journal entry saved!');
    }

    // Show all journal entries (non-archived)
    public function index()
    {
        $entries = JournalEntry::where('user_id', auth()->id())
                               ->where('archived', false)
                               ->with('quote')
                               ->latest()
                               ->get();

        return view('journal.index', compact('entries'));
    }

    // Show archived entries
    public function showArchived()
    {
        // ✅ Cleanup: delete entries older than 30 days
        JournalEntry::where('archived', true)
            ->where('archived_at', '<', now()->subDays(30))
            ->delete();

        $entries = JournalEntry::where('user_id', auth()->id())
                               ->where('archived', true)
                               ->with('quote')
                               ->latest()
                               ->get();

        return view('journal.archived', compact('entries'));
    }

    // Archive an entry
    public function archiveEntry($id)
    {
        $entry = JournalEntry::where('user_id', auth()->id())->findOrFail($id);

        $entry->archived = true;
        $entry->archived_at = now('Asia/Manila'); // ✅ set timestamp here
        $entry->save();

        return redirect()->back()->with('success', 'Entry archived successfully.');
    }

    // Restore an archived entry
    public function restore($id)
    {
        $entry = JournalEntry::where('user_id', auth()->id())
                             ->where('archived', true)
                             ->findOrFail($id);

        $entry->archived = false;
        $entry->archived_at = null; // ✅ clear timestamp when restoring
        $entry->save();

        return redirect()->back()->with('success', 'Entry restored.');
    }

    // Toggle sharing with consultant
    public function share($id)
    {
        $entry = JournalEntry::where('user_id', auth()->id())->findOrFail($id);

        $entry->shared_with_consultant = !$entry->shared_with_consultant;
        $entry->save();

        return redirect()->route('journal.index')->with('success', 'Sharing preference updated.');
    }

    // Edit entry (only if created today)
    public function edit($id)
    {
        $entry = JournalEntry::where('user_id', auth()->id())->findOrFail($id);

        if (!$entry->created_at->isToday()) {
            return redirect()->route('journal.index')->with('error', 'You can only edit entries on the same day.');
        }

        return view('journal.edit', compact('entry'));
    }

    // Update entry (only if within 24h)
    public function update(Request $request, $id)
    {
        $request->validate([
            'reflection' => 'required|string|max:500',
        ]);

        $entry = JournalEntry::where('user_id', auth()->id())->findOrFail($id);

        if ($entry->created_at->timezone(config('app.timezone'))->lte(now()->subDay())) {
            return redirect()->route('journal.index')
                ->with('error', 'Editing is only allowed within 24 hours of creation.');
        }

        $entry->reflection = $request->reflection;
        $entry->save();

        return redirect()->route('journal.index')->with('success', 'Journal entry updated.');
    }
}