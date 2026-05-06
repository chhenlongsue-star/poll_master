<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->input('range');

        if ($range && str_contains($range, 'to')) {
            [$start, $end] = explode(' to ', $range);
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate = Carbon::parse($end)->endOfDay();
        } else {
            $startDate = now()->subDays(6)->startOfDay();
            $endDate = now()->endOfDay();
        }

        $previousStart = $startDate->copy()->subDays($startDate->diffInDays($endDate)+1);
        $previousEnd = $startDate->copy()->subDay();

        $dates = [];
        $votes = [];
        $usersData = [];
        $pollsData = [];

        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $dates[] = $date->format('M d');

            $votes[] = Vote::whereDate('created_at', $date)->count();
            $usersData[] = User::whereDate('created_at', $date)->count();
            $pollsData[] = Poll::whereDate('created_at', $date)->count();
        }

        $totalUsers = User::count();
        $totalVotes = Vote::count();
        $totalPolls = Poll::count();
        $activeUsersCount = User::where('last_seen_at','>=',now()->subMinutes(5))->count();

        $topUsers = User::withCount('polls')
            ->orderByDesc('polls_count')
            ->take(5)
            ->get();

        $topPolls = Poll::withCount('votes')
            ->orderByDesc('votes_count')
            ->take(5)
            ->get();

        $recentActivities = Vote::latest()->take(5)->get();

        $users = User::latest()->paginate(10);

        return view('admin.dashboard', compact(
            'dates','votes','usersData','pollsData',
            'totalUsers','totalVotes','totalPolls','activeUsersCount',
            'topUsers','topPolls','recentActivities','users'
        ));
    }

    public function updateRole(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            return back();
        }

        $request->validate(['role'=>'required|in:user,sub_admin,admin']);

        if (Auth::id() === $user->id) return back();

        $user->update(['role'=>$request->role]);

        return back();
    }

    public function toggleStatus(User $user)
    {
        if (Auth::user()->role === 'sub_admin' && $user->role === 'admin') {
            return back();
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        return back();
    }
}