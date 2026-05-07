<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="py-10 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 space-y-8">

            {{-- KPI CARDS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Users</p>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalUsers }}</h3>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Active (5m)</p>
                    <h3 class="text-2xl font-black text-green-500">{{ $activeUsersCount }}</h3>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Votes</p>
                    <h3 class="text-2xl font-black text-indigo-500">{{ $totalVotes }}</h3>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow transition-colors">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Polls</p>
                    <h3 class="text-2xl font-black text-yellow-500">{{ $totalPolls }}</h3>
                </div>
            </div>

            {{-- CHART SECTION --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow transition-colors">
                <div class="flex flex-col md:flex-row md:justify-between gap-4 mb-6">
                    <form method="GET" class="flex gap-2 items-center">
                        <input id="range" name="range"
                               class="border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs rounded-lg dark:bg-gray-700 dark:text-white shadow-sm"
                               placeholder="Select range">
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 text-xs font-bold rounded-lg transition uppercase tracking-widest">
                            Apply
                        </button>
                    </form>

                    <div class="flex gap-2">
                        <a href="?compare=week"
                           class="px-4 py-1.5 text-xs font-bold rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:opacity-80 transition uppercase tracking-widest">
                            Compare Weeks
                        </a>
                        <button onclick="downloadPDF()"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1.5 text-xs font-bold rounded-lg transition uppercase tracking-widest">
                            Export PDF
                        </button>
                    </div>
                </div>

                <div class="relative h-[300px] w-full">
                    <canvas id="chart"></canvas>
                </div>
            </div>

            {{-- TABLE + USERS --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden transition-colors">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr class="text-[10px] font-black uppercase tracking-widest text-gray-500 dark:text-gray-400">
                                <th class="px-6 py-4 text-left">User Profile</th>
                                <th class="px-6 py-4 text-center">Role Management</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition">
                                
                                {{-- USER PROFILE WITH IMAGE --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.users.show', $user) }}" class="flex items-center group">
                                        <div class="relative">
                                            <img src="{{ $user->avatar }}" 
                                                 class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow-sm"
                                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF'">
                                            @if($user->last_seen_at >= now()->subMinutes(5))
                                                <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white dark:ring-gray-800"></span>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-black text-indigo-600 dark:text-indigo-400 group-hover:underline">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                        </div>
                                    </a>
                                </td>

                                {{-- ROLE DROPDOWN --}}
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.users.update-role', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role"
                                                class="bg-gray-50 dark:bg-gray-700 border-none text-[10px] font-black uppercase tracking-widest rounded-lg px-3 py-1 dark:text-white focus:ring-2 focus:ring-indigo-500 cursor-pointer"
                                                onchange="this.form.submit()">
                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="sub_admin" {{ $user->role == 'sub_admin' ? 'selected' : '' }}>Sub Admin</option>
                                            @if(auth()->user()->role === 'admin')
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            @endif
                                        </select>
                                    </form>
                                </td>

                                {{-- ACTION BUTTONS --}}
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    @if(!(auth()->user()->role === 'sub_admin' && $user->role === 'admin'))
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-xs font-black uppercase tracking-tighter px-3 py-1 rounded-md border transition
                                                {{ $user->is_banned 
                                                    ? 'text-emerald-500 border-emerald-500 hover:bg-emerald-50' 
                                                    : 'text-orange-500 border-orange-500 hover:bg-orange-50' }}">
                                                {{ $user->is_banned ? 'Unblock' : 'Block User' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-gray-50 dark:bg-gray-800/50 border-t dark:border-gray-700">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- LIBRARIES --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        // Init Date Picker
        flatpickr("#range", {
            mode: "range",
            dateFormat: "Y-m-d"
        });

        // Dynamic Chart Color Helper
        function getChartTheme() {
            const isDark = document.documentElement.classList.contains('dark') || 
                           window.matchMedia('(prefers-color-scheme: dark)').matches;

            return {
                text: isDark ? '#9CA3AF' : '#4B5563', // Gray-400 or Gray-600
                grid: isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)',
                font: 'Inter, ui-sans-serif, system-ui'
            };
        }

        const theme = getChartTheme();

        // Chart Configuration
        const ctx = document.getElementById('chart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [
                    { 
                        label: 'Votes', 
                        data: {!! json_encode($votes) !!}, 
                        borderColor: '#6366F1', 
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4 
                    },
                    { 
                        label: 'Users', 
                        data: {!! json_encode($usersData) !!}, 
                        borderColor: '#10B981', 
                        tension: 0.4 
                    },
                    { 
                        label: 'Polls', 
                        data: {!! json_encode($pollsData) !!}, 
                        borderColor: '#F59E0B', 
                        tension: 0.4 
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { 
                            color: theme.text,
                            usePointStyle: true,
                            font: { weight: 'bold', size: 11 }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: theme.text, font: { size: 10 } },
                        grid: { color: theme.grid }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: theme.text, font: { size: 10 } },
                        grid: { color: theme.grid }
                    }
                }
            }
        });

        // PDF Export
        function downloadPDF() {
            const chartCanvas = document.querySelector("#chart");
            html2canvas(chartCanvas, {
                backgroundColor: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff'
            }).then(canvas => {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('l', 'mm', 'a4');
                pdf.text("PollMaster Analytics Report", 10, 10);
                pdf.addImage(canvas.toDataURL("image/png"), "PNG", 10, 20, 280, 150);
                pdf.save("pollmaster-analytics.pdf");
            });
        }

        // Live refresh logic (log to console every minute)
        setInterval(() => {
            console.log("Stats synced at: " + new Date().toLocaleTimeString());
        }, 60000);
    </script>
</x-app-layout>