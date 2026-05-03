<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Stats for cards
        $totalUsers = User::count();
        $totalVotes = Vote::count();
        // $adminPollsCount = Poll::where('type', 'admin')->count();
        $totalPolls = Poll::count();
        
        // Active Users Logic (Using the last_seen_at column from our middleware)
        // $activeUsersCount = User::where('last_seen_at', '>=', now()->subMinutes(5))->count();
        $activeUsersCount = User::where('last_seen_at', '>=', now()->subMinutes(5))->count();

        // Data for Voting Graphic (Last 7 days)
        $votingData = Vote::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as aggregate'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // User list for management
        $users = User::latest()->paginate(10);

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalVotes', 
            'totalPolls',
            'activeUsersCount',
            'votingData',
            'users'
        ));
    }

    /**
     * Toggle User Status (Ban/Enable)
     */
   public function toggleStatus(User $user)
{
    if (auth()->id() === $user->id) {
        return back()->with('error', 'You cannot ban yourself.');
    }

    // Perform the toggle
    $user->is_banned = !$user->is_banned;
    $user->save();

    // Set message based on the NEW state
    // If is_banned is true, the message should say "banned"
    $message = $user->is_banned ? 'User account has been banned.' : 'User account has been unbanned.';
    
    return back()->with('success', $message);
}

    public function updateRole(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'role' => 'required|in:user,sub_admin,admin'
        ]);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', "{$user->name}'s role updated to " . ucfirst($request->role) . ".");
    }

    public function destroyUser(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized action.');
        }

        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function managePolls(Request $request)
{
    $query = Poll::with(['user', 'category'])->withCount('votes');

    // Search filter
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    $allPolls = $query->latest()->paginate(10);
    $categories = \App\Models\Category::all();

    return view('admin.manage-polls', compact('allPolls', 'categories'));
}
}