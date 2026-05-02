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
     * Display the user dashboard with filters and search.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category');

        $query = Poll::with(['user', 'category', 'options'])
            ->withCount('votes')
            ->where('is_active', true);

        // Filter by Search (Title or Description)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by Category
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Dashboard Sections (Cloning queries for different lists)
        $recentPolls = (clone $query)->latest()->take(6)->get();
        $popularPolls = (clone $query)->orderBy('votes_count', 'desc')->take(6)->get();
        $adminPolls = (clone $query)->where('type', 'admin')->latest()->take(10)->get();
        $userPolls = (clone $query)->where('type', 'user')->latest()->take(10)->get();

        $categories = Category::all();

        return view('dashboard', compact(
            'recentPolls', 
            'popularPolls', 
            'adminPolls', 
            'userPolls', 
            'categories', 
            'search'
        ));
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
        // Security check
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
        // Security check
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
        // Security check
        if (Auth::id() !== $poll->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'You do not have permission to delete this poll.');
        }

        // Deletes the poll. 
        // Note: Ensure your migrations use ->onDelete('cascade') for options and votes!
        $poll->delete();

        return redirect()->route('dashboard')->with('success', 'Poll has been removed successfully.');
    }

    public function myContent()
{
    $user = auth()->user();

    $myPolls = Poll::where('user_id', auth()->id())
                   ->orderBy('created_at', 'desc')
                   ->get();

    // Fetch polls the user has voted on
    $myVotes = \App\Models\Vote::where('user_id', auth()->id())
                                ->with('poll')
                                ->orderBy('created_at', 'desc')
                                ->get();

    return view('polls.my-content', compact('myPolls', 'myVotes'));
}
}