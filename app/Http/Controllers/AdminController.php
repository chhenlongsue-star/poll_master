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
    $range = $request->input('range', 7);

    $startDate = now()->subDays($range - 1)->startOfDay();
    $endDate = now()->endOfDay();

    $voteRaw = Vote::whereBetween('created_at', [$startDate, $endDate])
        ->selectRaw('DATE(created_at) as date, count(*) as total')
        ->groupBy('date')
        ->pluck('total', 'date');

    $userRaw = User::whereBetween('created_at', [$startDate, $endDate])
        ->selectRaw('DATE(created_at) as date, count(*) as total')
        ->groupBy('date')
        ->pluck('total', 'date');

    $pollRaw = Poll::whereBetween('created_at', [$startDate, $endDate])
        ->selectRaw('DATE(created_at) as date, count(*) as total')
        ->groupBy('date')
        ->pluck('total', 'date');

    $dates = [];
    $votes = [];
    $usersData = [];
    $pollsData = [];

    $current = $startDate->copy();

    while ($current <= $endDate) {
        $date = $current->format('Y-m-d');

        $dates[] = $date;
        $votes[] = $voteRaw[$date] ?? 0;
        $usersData[] = $userRaw[$date] ?? 0;
        $pollsData[] = $pollRaw[$date] ?? 0;

        $current->addDay();
    }

    $search = $request->input('search');

    $users = User::when($search, function($query) use ($search) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
    })->latest()->paginate(10)->withQueryString();

    return view('admin.dashboard', [
        'dates' => $dates,
        'votes' => $votes,
        'usersData' => $usersData,
        'pollsData' => $pollsData,
        'users' => $users,
        'range' => $range,
        'totalUsers' => User::count(),
        'totalVotes' => Vote::count(),
        'totalPolls' => Poll::count(),
        'activeUsersCount' => User::where('last_seen_at', '>=', now()->subMinutes(5))->count(),
    ]);
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

        return back()->with('success', 'User status updated successfully.');
    }

    public function togglePollStatus(Poll $poll)
    {
        $poll->is_active = !$poll->is_active;
        $poll->save();

        return back()->with('status', 'Poll status updated!');
    }

    public function updateRole(Request $request, User $user)
    {
        $currentUser = Auth::user();

        $request->validate([
            'role' => 'required|in:user,sub_admin,admin'
        ]);

        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        if ($currentUser->role === 'admin') {
            $user->update(['role' => $request->role]);
            return back()->with('success', 'Role updated successfully.');
        }

        if ($currentUser->role === 'sub_admin') {
            if ($user->role === 'admin') {
                return back()->with('error', 'Cannot modify admin.');
            }

            if ($user->role === 'user' && $request->role === 'sub_admin') {
                $user->update(['role' => 'sub_admin']);
                return back()->with('success', 'User promoted to sub_admin.');
            }

            return back()->with('error', 'Unauthorized role change.');
        }

        return back()->with('error', 'Unauthorized action.');
    }

    public function destroyUser(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->role !== 'admin') {
            return back()->with('error', 'Unauthorized action.');
        }

        if ($currentUser->id === $user->id) {
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