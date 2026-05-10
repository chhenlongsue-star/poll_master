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
    /**
     * Display the main admin dashboard with stats, charts, and user management.
     */
    public function index(Request $request)
    {
        // 1. Basic KPI Stats
        $totalUsers = User::count();
        $totalVotes = Vote::count();
        $totalPolls = Poll::count();
        $activeUsersCount = User::where('last_seen_at', '>=', now()->subMinutes(5))->count();

        // 2. Chart Logic: Prepare data for the last 7 days
        // This fixes the "Undefined variable $dates" error
        $range = request('range');

if ($range && str_contains($range, ' to ')) {

    [$start, $end] = explode(' to ', $range);

    $startDate = \Carbon\Carbon::parse($start)->startOfDay();
    $endDate = \Carbon\Carbon::parse($end)->endOfDay();

} else {

    $startDate = now()->subDays(6)->startOfDay();
    $endDate = now()->endOfDay();
}

$dates = [];
$votes = [];
$usersData = [];
$pollsData = [];

$currentDate = $startDate->copy();

while ($currentDate <= $endDate) {

    $formattedDate = $currentDate->format('Y-m-d');

    $dates[] = $currentDate->format('M d');

    $votes[] = Vote::whereDate('created_at', $formattedDate)->count();

    $usersData[] = User::whereDate('created_at', $formattedDate)->count();

    $pollsData[] = Poll::whereDate('created_at', $formattedDate)->count();

    $currentDate->addDay();
}

        // 3. User Management Table Logic (with Search)
        $search = $request->input('search');
        $users = User::where(function($query) use ($search) {
            if ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            }
        })->latest()->paginate(10)->withQueryString();

        // 4. Return view with all required variables
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalVotes', 
            'totalPolls', 
            'activeUsersCount', 
            'users', 
            'dates', 
            'votes', 
            'usersData', 
            'pollsData',
            'range'
        ));
    }

    /**
     * Show a specific user's profile and their polls.
     */
    public function showUser(User $user)
    {
        $polls = $user->polls()->withCount('votes')->latest()->paginate(10);
        return view('admin.users.show', compact('user', 'polls'));
    }

    /**
     * Toggle a user's ban status.
     * Rule: Sub-Admins cannot modify Admin accounts.
     */
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

    /**
     * Update a user's role.
     * Includes restrictions for Sub-Admins to prevent demotions or admin-level changes.
     */
    public function updateRole(Request $request, User $user)
{
    $currentUser = Auth::user();
    $request->validate(['role' => 'required|in:user,sub_admin,admin']);

    if ($currentUser->id === $user->id) {
        return back()->with('error', 'You cannot change your own role.');
    }

    if ($currentUser->role === 'sub_admin') {
        // Prevent Sub-Admins from touching Admin accounts or creating new Admins
        if ($user->role === 'admin' || $request->role === 'admin') {
            return back()->with('error', 'Unauthorized: Only Admins can manage Admin roles.');
        }
        // Prevent Sub-Admins from demoting other Sub-Admins
        if ($user->role === 'sub_admin' && $request->role === 'user') {
            return back()->with('error', 'Sub-admins do not have permission to demote accounts.');
        }
    }

    $user->update(['role' => $request->role]);
    return back()->with('success', "Role updated successfully.");
}

/**
 * Permanently delete a poll.
 */
public function destroyPoll(Poll $poll)
{
    // Restrict deletion to full Admins only
    if (Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized: Only full Admins can delete polls.');
    }

    $poll->delete(); // Ensure your Migration has ->onDelete('cascade') for votes
    
    return back()->with('success', 'Poll and all associated data deleted successfully.');
}

    /**
     * Permanently delete a user.
     * Rule: Only full Admins can delete users.
     */
    public function destroyUser(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized action. Only full Admins can delete users.');
        }
        
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }

    /**
     * View and filter all polls in the system.
     */
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

    /**
     * Toggle whether a poll is active or hidden.
     */
    public function togglePollStatus(Poll $poll)
    {
        $poll->is_active = !$poll->is_active;
        $poll->save();

        return back()->with('status', 'Poll status updated!');
    }

}