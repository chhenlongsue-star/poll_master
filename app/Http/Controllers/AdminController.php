<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Stats for cards
        $totalUsers = User::count();
        $totalVotes = Vote::count();
        $adminPollsCount = Poll::where('type', 'admin')->count();
        
        // Active Users Logic (Users who were active in the last 5 minutes)
        $activeUsersCount = User::where('updated_at', '>=', now()->subMinutes(5))->count();

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
            'adminPollsCount', 
            'activeUsersCount',
            'votingData',
            'users'
        ));
    }

    /**
     * Update user role (Promote/Demote)
     * Handles User, Sub-Admin, and Admin roles
     */
    public function updateRole(Request $request, User $user)
    {
        // Safety check: Only the main admin can manage roles
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized action.');
        }

        // Validate the incoming role
        $request->validate([
            'role' => 'required|in:user,sub_admin,admin'
        ]);

        // Prevent the admin from demoting themselves (locking themselves out)
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot change your own administrative role.');
        }

        // Update the role
        $user->update([
            'role' => $request->role
        ]);

        return back()->with('success', "{$user->name}'s role updated to " . ucfirst($request->role) . ".");
    }

    /**
     * Remove a user from the system
     */
    public function destroyUser(User $user)
    {
        // Only main admin can delete users
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized action.');
        }

        // Safety: Cannot delete the current logged-in admin
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account from here.');
        }

        // Safety: Prevent deleting other top-level admins if necessary
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'The system must have at least one main Admin.');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}