<x-app-layout>
    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow border-b-4 border-blue-500">
                    <p class="text-gray-500 text-sm font-semibold uppercase">Total Users</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</h3>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-b-4 border-green-500">
                    <p class="text-gray-500 text-sm font-semibold uppercase">Active Now</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $activeUsersCount }}</h3>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-b-4 border-purple-500">
                    <p class="text-gray-500 text-sm font-semibold uppercase">Total Votes</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalVotes }}</h3>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-b-4 border-yellow-500">
                    <p class="text-gray-500 text-sm font-semibold uppercase">Admin Polls</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $adminPollsCount }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow mb-8">
                <h3 class="text-lg font-bold mb-4 text-gray-700">Voting Activity (Last 7 Days)</h3>
                <canvas id="voteChart" height="100"></canvas>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-700">User Management</h3>
                </div>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 uppercase text-xs font-bold tracking-wider">
                            <th class="px-5 py-3 border-b text-left">User Info</th>
                            <th class="px-5 py-3 border-b text-left">Role Assignment</th>
                            <th class="px-5 py-3 border-b text-right">Danger Zone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-5 border-b bg-white text-sm">
                                <div class="flex flex-col">
                                    <span class="text-gray-900 font-semibold">{{ $user->name }}</span>
                                    <span class="text-gray-500 text-xs">{{ $user->email }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b bg-white text-sm">
                                @if(Auth::id() !== $user->id)
                                    <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="flex items-center">
                                        @csrf
                                        <select name="role" onchange="this.form.submit()" class="text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>USER</option>
                                            <option value="sub_admin" {{ $user->role == 'sub_admin' ? 'selected' : '' }}>SUB-ADMIN</option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>ADMIN</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs font-bold uppercase">
                                        CURRENT ADMIN
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b bg-white text-sm text-right">
                                @if($user->role !== 'admin' || \App\Models\User::where('role', 'admin')->count() > 1)                                    @if(Auth::id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900 font-medium text-xs flex items-center justify-end ml-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-5 py-5 bg-white border-t">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('voteChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($votingData->pluck('date')) !!},
                datasets: [{
                    label: 'Votes Cast',
                    data: {!! json_encode($votingData->pluck('aggregate')) !!},
                    borderColor: 'rgb(79, 70, 229)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: 'rgb(79, 70, 229)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
</x-app-layout>