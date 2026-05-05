<x-app-layout>
    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @php
                $currentUser = auth()->user();
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow border-b-4 border-blue-500 text-center">
                    <p class="text-gray-500 text-xs font-bold uppercase">Total Users</p>
                    <h3 class="text-2xl font-bold">{{ $totalUsers }}</h3>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-b-4 border-green-500 text-center">
                    <p class="text-gray-500 text-xs font-bold uppercase">Active (5m)</p>
                    <h3 class="text-2xl font-bold">{{ $activeUsersCount }}</h3>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-b-4 border-purple-500 text-center">
                    <p class="text-gray-500 text-xs font-bold uppercase">Total Votes</p>
                    <h3 class="text-2xl font-bold">{{ $totalVotes }}</h3>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-b-4 border-yellow-500 text-center">
                    <p class="text-gray-500 text-xs font-bold uppercase">Total Polls</p>
                    <h3 class="text-2xl font-bold">{{ $totalPolls }}</h3>
                </div>
            </div>

           <div class="bg-white p-6 rounded-lg shadow mb-8">

    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-gray-700">Analytics Overview</h3>

        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard', ['range' => 7]) }}"
               class="px-3 py-1 text-xs rounded {{ $range == 7 ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">
                7 Days
            </a>

            <a href="{{ route('admin.dashboard', ['range' => 30]) }}"
               class="px-3 py-1 text-xs rounded {{ $range == 30 ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">
                30 Days
            </a>
        </div>
    </div>

    <canvas id="voteChart" height="90"></canvas>
</div>


            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h3 class="text-lg font-bold text-gray-700">User Management</h3>
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="w-full md:w-1/3 flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search name or email..." 
                               class="w-full rounded-md border-gray-300 text-sm shadow-sm">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-xs font-bold">SEARCH</button>
                    </form>
                </div>

                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold tracking-widest">
                            <th class="px-6 py-4 text-left">User</th>
                            <th class="px-6 py-4 text-left">Role</th>
                            <th class="px-6 py-4 text-right">Quick Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-indigo-50/50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.show', $user) }}" class="flex items-center group">
                                    <img src="{{ $user->avatar }}" class="h-8 w-8 rounded-full mr-3 border border-gray-200">
                                    <div class="flex flex-col">
                                        <span class="text-indigo-600 font-bold group-hover:underline">{{ $user->name }}</span>
                                        <span class="text-gray-400 text-xs">{{ $user->email }}</span>
                                    </div>
                                </a>
                            </td>

                            <td class="px-6 py-4">
                                <form action="{{ route('admin.users.update-role', $user) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <select name="role" onchange="this.form.submit()" 
                                        class="bg-gray-50 border border-gray-200 text-gray-600 text-[10px] font-black uppercase tracking-widest rounded-lg p-1.5 cursor-pointer hover:bg-white transition">

                                        <option value="user"
                                            {{ $user->role == 'user' ? 'selected' : '' }}
                                            @if($currentUser->role === 'sub_admin' && $user->role !== 'user') disabled @endif>
                                            User
                                        </option>

                                        <option value="sub_admin"
                                            {{ $user->role == 'sub_admin' ? 'selected' : '' }}
                                            @if($currentUser->role === 'sub_admin' && $user->role !== 'user') disabled @endif>
                                            Sub_Admin
                                        </option>

                                        @if($currentUser->role === 'admin')
                                            <option value="admin"
                                                {{ $user->role == 'admin' ? 'selected' : '' }}>
                                                Admin
                                            </option>
                                        @endif

                                    </select>
                                </form>
                            </td>

                            <td class="px-6 py-4 text-right">
                                @if(!($currentUser->role === 'sub_admin' && $user->role === 'admin'))
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="text-[10px] font-black uppercase tracking-tighter {{ $user->is_banned ? 'text-green-500' : 'text-orange-500' }}">
                                            {{ $user->is_banned ? 'Unblock' : 'Block' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-300 text-xs">Protected</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="px-6 py-4 bg-gray-50 border-t">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = {!! json_encode($dates) !!};

    const votes = {!! json_encode($votes) !!};
    const users = {!! json_encode($usersData) !!};
    const polls = {!! json_encode($pollsData) !!};

    new Chart(document.getElementById('voteChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Votes',
                    data: votes,
                    borderColor: '#4F46E5',
                    backgroundColor: 'transparent',
                    tension: 0.4
                },
                {
                    label: 'New Users',
                    data: users,
                    borderColor: '#10B981',
                    backgroundColor: 'transparent',
                    tension: 0.4
                },
                {
                    label: 'New Polls',
                    data: polls,
                    borderColor: '#F59E0B',
                    backgroundColor: 'transparent',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</x-app-layout>