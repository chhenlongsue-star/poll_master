<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poll;
use App\Models\Vote;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', now()->format('Y-m-d'));

        $votingData = Vote::whereDate('created_at', $selectedDate)
            ->selectRaw('EXTRACT(HOUR FROM created_at) as hour, count(*) as aggregate')
            ->groupBy('hour')
            ->orderBy('hour', 'ASC')
            ->get();

        $search = $request->input('search');
        $users = User::when($search, function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })->latest()->paginate(10)->withQueryString();

        $totalUsers = User::count();
        $totalVotes = Vote::count();
        $totalPolls = Poll::count();
        $activeUsersCount = User::where('last_seen_at', '>=', now()->subMinutes(5))->count();

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalVotes', 
            'totalPolls',
            'activeUsersCount',
            'votingData',
            'users',
            'selectedDate'
        ));
    }

    public function showUser(User $user)
    {
        $polls = $user->polls()->withCount('votes')->latest()->paginate(10);
        
        return view('admin.users.show', compact('user', 'polls'));
    }

    public function toggleStatus(User $user)
    {
        $currentUser = Auth::user();
        if ($currentUser->role === 'sub_admin' && $user->role === 'admin') {
            return back()->with('error', 'Security Alert: Sub-Admins cannot modify Admin accounts.');
        }
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        return back()->with('success', "User status updated successfully.");
    }

    public function togglePollStatus(Poll $poll)
    {
        $poll->is_active = !$poll->is_active;
        $poll->save();

        return back()->with('status', 'Poll status updated!');
    }

    public function updateRole(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate(['role' => 'required|in:user,sub_admin,admin']);

        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role' => $request->role]);
        return back()->with('success', "Role updated successfully.");
    }

    public function destroyUser(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized action.');
        }
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }

    public function managePolls(Request $request)
    {
        $query = Poll::with(['user', 'category'])->withCount('votes');
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $allPolls = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.manage-polls', compact('allPolls', 'categories'));
    }
}