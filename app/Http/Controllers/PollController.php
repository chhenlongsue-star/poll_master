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
     * Only displays ACTIVE polls.
     */
    public function index(Request $request)
    {
        // Global filter: Only show active polls on the public dashboard
        $query = Poll::with(['user', 'category'])
            ->withCount('votes')
            ->where('is_active', true); 

        $tab = $request->query('tab', 'all');

        // 1. Handle Tabs Logic
        switch ($tab) {
            case 'official':
                $query->where('type', 'admin');
                break;

            case 'trending':
                $query->orderBy('votes_count', 'desc');
                break;

            case 'community':
                $query->where('type', 'user');
                break;

            case 'favorites':
                $query->whereHas('favoritedBy', function($q) {
                    $q->where('user_id', Auth::id());
                });
                break;

            default:
                $query->latest();
                break;
        }

        // 2. Search Logic
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

        $allPolls = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('dashboard', compact('allPolls', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('polls.create', compact('categories'));
    }

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
            'is_active' => true, // Default to active on creation
        ]);

        foreach ($request->options as $optionText) {
            $poll->options()->create(['option_text' => $optionText]);
        }

        return redirect()->route('dashboard')->with('success', 'Poll created successfully!');
    }

    public function show(Poll $poll)
    {
        // Prevent normal users from viewing inactive polls via direct URL
        if (!$poll->is_active && !in_array(Auth::user()->role, ['admin', 'sub_admin'])) {
            abort(404, 'This poll has been deactivated by an administrator.');
        }

        $poll->load(['options', 'user', 'category'])->loadCount('votes');
        
        $userVote = Vote::where('poll_id', $poll->id)
                        ->where('user_id', Auth::id())
                        ->first();

        return view('polls.show', compact('poll', 'userVote'));
    }

    public function vote(Request $request, Poll $poll)
    {
        if (!$poll->is_active) {
            return back()->with('error', 'Voting is closed for this poll.');
        }

        $request->validate([
            'option_id' => 'required|exists:options,id,poll_id,' . $poll->id,
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

    public function edit(Poll $poll)
    {
        if (Auth::id() !== $poll->user_id && !in_array(Auth::user()->role, ['admin', 'sub_admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();
        return view('polls.edit', compact('poll', 'categories'));
    }

    public function update(Request $request, Poll $poll)
    {
        if (Auth::id() !== $poll->user_id && !in_array(Auth::user()->role, ['admin', 'sub_admin'])) {
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

    public function destroy(Poll $poll)
    {
        if (Auth::id() !== $poll->user_id && !in_array(Auth::user()->role, ['admin', 'sub_admin'])) {
            return redirect()->back()->with('error', 'You do not have permission to delete this poll.');
        }

        $poll->delete();
        return redirect()->back()->with('success', 'Poll has been removed successfully.');
    }

    public function myContent(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'my-polls');

        if ($tab === 'vote-history') {
            $content = Poll::whereHas('votes', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['user', 'category'])->withCount('votes')->latest()->paginate(12);
        } else {
            $content = Poll::where('user_id', $user->id)
                ->with(['category'])
                ->withCount('votes')
                ->latest()
                ->paginate(12);
        }

        return view('polls.my-content', compact('content', 'tab'));
    }

    public function toggleFavourite(Poll $poll)
    {
        $user = Auth::user();
        $user->favouritePolls()->toggle($poll->id);
        return back();
    }
}