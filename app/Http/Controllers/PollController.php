<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\Category;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    /**
     * Display the user dashboard with functional tabs, filters, and search.
     */
    public function index(Request $request)
    {
        $query = Poll::with(['user', 'category'])->withCount('votes');
        $tab = $request->query('tab', 'all');

        // 1. Handle Tabs Logic
        switch ($tab) {
            case 'official':
                // Shows polls created by Admins or Sub-Admins
                $query->where('type', 'admin');
                break;

            case 'trending':
                // Orders by highest vote count
                $query->orderBy('votes_count', 'desc');
                break;

            case 'community':
                // Shows polls created by regular users
                $query->where('type', 'user');
                break;

            case 'favorites':
                // Assuming you have a 'favorites' relationship or table
                $query->whereHas('favoritedBy', function($q) {
                    $q->where('user_id', Auth::id());
                });
                break;

            default:
                // 'all' tab or fallback
                $query->latest();
                break;
        }

        // 2. Search Logic (Title or Description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 3. Category Filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // 4. Finalize Query with Pagination
        // withQueryString() ensures that when you click page 2, the 'tab' and 'search' stay in the URL
        $allPolls = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('dashboard', compact('allPolls', 'categories'));
    }

    /**
     * Show the form for creating a new poll.
     */
    public function create()
    {
        $categories = Category::all();
        return view('polls.create', compact('categories'));
    }

    /**
     * Store a newly created poll.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        // Automatically determine if this is an official poll based on user role
        $type = in_array(Auth::user()->role, ['admin', 'sub_admin']) ? 'admin' : 'user';

        $poll = Poll::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'type' => $type,
        ]);

        foreach ($request->options as $optionText) {
            $poll->options()->create(['option_text' => $optionText]);
        }

        return redirect()->route('dashboard')->with('success', 'Poll created successfully!');
    }

    /**
     * Display a specific poll and its results.
     */
    public function show(Poll $poll)
    {
        $poll->load(['options', 'user', 'category'])->loadCount('votes');
        
        $userVote = Vote::where('poll_id', $poll->id)
                        ->where('user_id', Auth::id())
                        ->first();

        return view('polls.show', compact('poll', 'userVote'));
    }

    /**
     * Handle the voting logic.
     */
    public function vote(Request $request, Poll $poll)
    {
        $request->validate([
            'option_id' => 'required|exists:options,id',
        ]);

        $alreadyVoted = Vote::where('poll_id', $poll->id)
                            ->where('user_id', Auth::id())
                            ->exists();

        if ($alreadyVoted) {
            return back()->with('error', 'You have already voted on this poll.');
        }

        Vote::create([
            'user_id' => Auth::id(),
            'poll_id' => $poll->id,
            'option_id' => $request->option_id,
        ]);

        return redirect()->route('polls.show', $poll)->with('success', 'Thank you for voting!');
    }

    /**
     * Show the form for editing the poll.
     */
    public function edit(Poll $poll)
    {
        if (Auth::id() !== $poll->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();
        return view('polls.edit', compact('poll', 'categories'));
    }

    /**
     * Update the poll in storage.
     */
    public function update(Request $request, Poll $poll)
    {
        if (Auth::id() !== $poll->user_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $poll->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Poll updated successfully!');
    }

    /**
     * Remove the poll from storage.
     */
    public function destroy(Poll $poll)
    {
        if (Auth::id() !== $poll->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'You do not have permission to delete this poll.');
        }

        $poll->delete();

        return redirect()->route('dashboard')->with('success', 'Poll has been removed successfully.');
    }

    /**
     * Manage user's own created polls and voting history.
     */
    public function myContent(Request $request)
{
    $user = auth()->user();
    $tab = $request->get('tab', 'my-polls');

    if ($tab === 'vote-history') {
        // Get polls the user has voted on
        $content = Poll::whereHas('votes', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['user', 'category'])->withCount('votes')->latest()->paginate(12);
    } else {
        // Get polls the user created
        $content = Poll::where('user_id', $user->id)
            ->with(['category'])
            ->withCount('votes')
            ->latest()
            ->paginate(12);
    }

    return view('polls.my-content', compact('content', 'tab'));
}

    /**
     * Toggle Favorite status for a poll.
     */
    public function toggleFavourite(Poll $poll)
    {
        $user = Auth::user();
        
        $user->favouritePolls()->toggle($poll->id);

        return back();
    }
}