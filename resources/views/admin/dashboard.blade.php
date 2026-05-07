<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="py-10 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 space-y-8">

            {{-- HEADER & SEARCH --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">System Overview</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Real-time analytics and user management.</p>
                </div>
                
                <form method="GET" action="{{ route('admin.dashboard') }}" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search users..." 
                           class="w-full md:w-72 pl-10 pr-4 py-2 bg-white dark:bg-gray-800 border-none rounded-xl shadow-sm text-sm dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </form>
            </div>

            {{-- KPI CARDS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-transparent dark:border-gray-700 transition-all">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Total Users</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</h3>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-transparent dark:border-gray-700 transition-all">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Active Now</p>
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <h3 class="text-3xl font-black text-green-500">{{ $activeUsersCount }}</h3>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-transparent dark:border-gray-700 transition-all">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Total Votes</p>
                    <h3 class="text-3xl font-black text-indigo-500">{{ number_format($totalVotes) }}</h3>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-transparent dark:border-gray-700 transition-all">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Total Polls</p>
                    <h3 class="text-3xl font-black text-yellow-500">{{ number_format($totalPolls) }}</h3>
                </div>
            </div>

            {{-- CHART SECTION --}}
            <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-transparent dark:border-gray-700 transition-all">
                <div class="flex flex-col md:flex-row md:justify-between items-center gap-4 mb-8">
                    <div class="flex flex-col gap-1">
                        <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-xs">Activity Trends</h4>
                        <p class="text-[10px] text-gray-500">Engagement metrics over the last 7 days.</p>
                    </div>

                    <div class="flex flex-wrap justify-center gap-3">
                        <form method="GET" class="flex gap-2">
                            <input id="range" name="range" readonly
                                   class="border-none bg-gray-100 dark:bg-gray-700 px-4 py-2 text-xs rounded-xl dark:text-white shadow-inner w-44"
                                   placeholder="Select Date Range">
                            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 text-[10px] font-black rounded-xl transition uppercase tracking-widest">
                                Filter
                            </button>
                        </form>
                        <button onclick="downloadPDF()"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 text-[10px] font-black rounded-xl transition uppercase tracking-widest">
                            Export PDF
                        </button>
                    </div>
                </div>

                <div class="relative h-[350px] w-full">
                    <canvas id="chart"></canvas>
                </div>
            </div>

            {{-- USERS TABLE --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-transparent dark:border-gray-700 overflow-hidden transition-all">
                <div class="px-8 py-5 border-b dark:border-gray-700 flex justify-between items-center">
                    <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-widest text-xs">User Database</h4>
                    <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-black rounded-full uppercase">
                        {{ $users->total() }} Members
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50/50 dark:bg-gray-900/30">
                            <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                <th class="px-8 py-4 text-left">Identity</th>
                                <th class="px-8 py-4 text-center">Authorization</th>
                                <th class="px-8 py-4 text-right">Account Security</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <a href="{{ route('admin.users.show', $user) }}" class="flex items-center group">
                                        <div class="relative">
                                            <img src="{{ $user->avatar }}" 
                                                 class="h-11 w-11 rounded-xl object-cover border-2 border-white dark:border-gray-600 shadow-sm transition group-hover:scale-105"
                                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=6366F1&background=EEF2FF&bold=true'">
                                            @if($user->last_seen_at >= now()->subMinutes(5))
                                                <span class="absolute -top-1 -right-1 block h-3.5 w-3.5 rounded-full bg-green-500 border-2 border-white dark:border-gray-800"></span>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-black text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ $user->name }}</div>
                                            <div class="text-[11px] text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                        </div>
                                    </a>
                                </td>

                                <td class="px-8 py-5 text-center whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.users.update-role', $user) }}">
                                        @csrf @method('PATCH')
                                        <select name="role"
                                                class="bg-gray-100 dark:bg-gray-700 border-none text-[10px] font-black uppercase tracking-widest rounded-lg px-4 py-1.5 dark:text-white focus:ring-2 focus:ring-indigo-500 cursor-pointer appearance-none shadow-sm"
                                                onchange="this.form.submit()">
                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="sub_admin" {{ $user->role == 'sub_admin' ? 'selected' : '' }}>Sub Admin</option>
                                            @if(auth()->user()->role === 'admin')
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            @endif
                                        </select>
                                    </form>
                                </td>

                                <td class="px-8 py-5 text-right whitespace-nowrap">
                                    @if(!(auth()->user()->role === 'sub_admin' && $user->role === 'admin'))
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <button class="text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-lg border transition
                                                {{ $user->is_banned 
                                                    ? 'text-emerald-500 border-emerald-500 hover:bg-emerald-500 hover:text-white shadow-lg shadow-emerald-500/20' 
                                                    : 'text-orange-500 border-orange-500 hover:bg-orange-500 hover:text-white shadow-lg shadow-orange-500/20' }}">
                                                {{ $user->is_banned ? 'Unrestrict' : 'Restrict Access' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-900/30 border-t dark:border-gray-700">
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
            dateFormat: "Y-m-d",
            theme: document.documentElement.classList.contains('dark') ? "dark" : "light"
        });

        // Chart Theme Detection
        function getChartColors() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                text: isDark ? '#9CA3AF' : '#6B7280',
                grid: isDark ? '#374151' : '#F3F4F6',
            };
        }

        const colors = getChartColors();
        const ctx = document.getElementById('chart').getContext('2d');
        
        const myChart = new Chart(ctx, {
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
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    { 
                        label: 'Users', 
                        data: {!! json_encode($usersData) !!}, 
                        borderColor: '#10B981', 
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 0
                    },
                    { 
                        label: 'Polls', 
                        data: {!! json_encode($pollsData) !!}, 
                        borderColor: '#F59E0B', 
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: colors.text, font: { weight: 'bold', size: 10 }, usePointStyle: true }
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleFont: { size: 12, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 10
                    }
                },
                scales: {
                    x: {
                        ticks: { color: colors.text, font: { size: 10 } },
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: colors.text, font: { size: 10 }, stepSize: 1 },
                        grid: { color: colors.grid }
                    }
                }
            }
        });

        // PDF Export Fix
        function downloadPDF() {
            const container = document.querySelector(".max-w-7xl");
            html2canvas(container, {
                backgroundColor: document.documentElement.classList.contains('dark') ? '#111827' : '#f3f4f6',
                scale: 2
            }).then(canvas => {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('l', 'mm', 'a4');
                const imgData = canvas.toDataURL("image/png");
                pdf.addImage(imgData, "PNG", 10, 10, 277, 190);
                pdf.save("pollmaster-admin-report.pdf");
            });
        }
    </script>
</x-app-layout>