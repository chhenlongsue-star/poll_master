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
    public function index()
    {
        // Stats for cards
        $totalUsers = User::count();
        $totalVotes = Vote::count();
        $totalPolls = Poll::count();
        
        // Active Users Logic (Users active in the last 5 minutes)
        $activeUsersCount = User::where('last_seen_at', '>=', now()->subMinutes(5))->count();

        // Data for Voting Graphic (Last 7 days)
        $votingData = Vote::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as aggregate'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // User list for management
        $users = User::latest()->paginate(10)->withQueryString();

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
     * Toggle User Status (Ban/Unban)
     */
    public function toggleStatus(User $user)
    {
        $currentUser = Auth::user();

        // 🛡️ THE SHIELD: Block Sub-Admins from touching Admins
        if ($currentUser->role === 'sub_admin' && $user->role === 'admin') {
            return back()->with('error', 'Security Alert: Sub-Admins cannot modify Admin accounts.');
        }

        // 🛡️ SELF-PROTECTION: Don't let an admin ban themselves
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        // UPDATED: Using 'is_banned' to match your Blade UI
        $user->is_banned = !$user->is_banned;
        $user->save();

        $statusMessage = $user->is_banned ? "banned" : "unbanned";
        return back()->with('success', "User has been successfully {$statusMessage}.");
    }

    public function togglePollStatus(\App\Models\Poll $poll)
{
    $poll->is_active = !$poll->is_active;
    $poll->save();

    return back()->with('status', 'Poll status updated!');
}

    /**
     * Change a User's Role (Admin Only)
     */
    public function updateRole(Request $request, User $user)
    {
        // This is a backup check; the Route Middleware also handles this
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'role' => 'required|in:user,sub_admin,admin'
        ]);

        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', "{$user->name}'s role updated to " . ucfirst($request->role) . ".");
    }

    /**
     * Delete a User Account (Admin Only)
     */
    public function destroyUser(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized action.');
        }

        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * View and Moderate All Polls
     */
    public function managePolls(Request $request)
    {
        $query = Poll::with(['user', 'category'])->withCount('votes');

        // Filter by Search Keyword
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by Category ID
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $allPolls = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.manage-polls', compact('allPolls', 'categories'));
    }
}