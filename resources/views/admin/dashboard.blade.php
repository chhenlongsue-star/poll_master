<x-app-layout>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="py-10 bg-gray-100 dark:bg-gray-900 min-h-screen">

<div class="max-w-7xl mx-auto px-4 space-y-8">

{{-- KPI CARDS --}}
<div class="grid md:grid-cols-4 gap-6">

<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
<p class="text-xs text-gray-400">Users</p>
<h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</h3>
</div>

<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
<p class="text-xs text-gray-400">Active</p>
<h3 class="text-2xl font-bold text-green-500">{{ $activeUsersCount }}</h3>
</div>

<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
<p class="text-xs text-gray-400">Votes</p>
<h3 class="text-2xl font-bold text-indigo-500">{{ $totalVotes }}</h3>
</div>

<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
<p class="text-xs text-gray-400">Polls</p>
<h3 class="text-2xl font-bold text-yellow-500">{{ $totalPolls }}</h3>
</div>

</div>

{{-- CHART SECTION --}}
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

<div class="flex flex-col md:flex-row md:justify-between gap-4 mb-4">

<form method="GET" class="flex gap-2 items-center">
<input id="range" name="range"
class="border px-3 py-1 text-xs rounded dark:bg-gray-700 dark:text-white"
placeholder="Select range">
<button class="bg-indigo-600 text-white px-3 py-1 text-xs rounded">
Apply
</button>
</form>

<div class="flex gap-2">

<a href="?compare=week"
class="px-3 py-1 text-xs rounded bg-gray-200 dark:bg-gray-700">
This vs Last Week
</a>

<button onclick="downloadPDF()"
class="bg-green-600 text-white px-3 py-1 text-xs rounded">
Export PDF
</button>

</div>

</div>

<canvas id="chart" height="100"></canvas>

</div>

{{-- TABLE + USERS --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">

<table class="min-w-full text-sm">

<thead class="bg-gray-50 dark:bg-gray-700">
<tr>
<th class="px-4 py-2 text-left">User</th>
<th class="px-4 py-2">Role</th>
<th class="px-4 py-2 text-right">Action</th>
</tr>
</thead>

<tbody>

@foreach($users as $user)
<tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

{{-- CLICKABLE PROFILE --}}
<td class="px-4 py-2">
<a href="{{ route('admin.users.show', $user) }}"
class="text-indigo-600 hover:underline font-medium">
{{ $user->name }}
</a>
</td>

<td class="px-4 py-2 text-center">

<form method="POST" action="{{ route('admin.users.update-role',$user) }}">
@csrf
@method('PATCH')

<select name="role"
class="border text-xs rounded px-2 dark:bg-gray-700 dark:text-white"
onchange="this.form.submit()">

<option value="user" {{ $user->role=='user'?'selected':'' }}>User</option>
<option value="sub_admin" {{ $user->role=='sub_admin'?'selected':'' }}>Sub Admin</option>

@if(auth()->user()->role === 'admin')
<option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
@endif

</select>

</form>

</td>

<td class="px-4 py-2 text-right">

@if(!(auth()->user()->role==='sub_admin' && $user->role==='admin'))

<form method="POST" action="{{ route('admin.users.toggle-status',$user) }}">
@csrf
@method('PATCH')

<button class="text-xs font-bold {{ $user->is_banned ? 'text-green-500' : 'text-orange-500' }}">
{{ $user->is_banned ? 'Unblock' : 'Block' }}
</button>

</form>

@endif

</td>

</tr>
@endforeach

</tbody>
</table>

<div class="p-4">
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

flatpickr("#range", {
    mode: "range",
    dateFormat: "Y-m-d"
});

function getColors() {
    const dark = document.documentElement.classList.contains('dark');

    return {
        text: dark ? '#fff' : '#111',
        grid: dark ? '#333' : '#eee'
    };
}

const colors = getColors();

new Chart(document.getElementById('chart'), {
type: 'line',
data: {
labels: {!! json_encode($dates) !!},
datasets: [
{ label: 'Votes', data: {!! json_encode($votes) !!}, borderColor: '#4F46E5' },
{ label: 'Users', data: {!! json_encode($usersData) !!}, borderColor: '#10B981' },
{ label: 'Polls', data: {!! json_encode($pollsData) !!}, borderColor: '#F59E0B' }
]
},
options: {
responsive: true,
plugins: {
legend: {
labels: { color: colors.text }
}
},
scales: {
x: {
ticks: { color: colors.text },
grid: { color: colors.grid }
},
y: {
ticks: { color: colors.text },
grid: { color: colors.grid }
}
}
}
});

function downloadPDF() {
html2canvas(document.querySelector("#chart")).then(canvas => {
const { jsPDF } = window.jspdf;
const pdf = new jsPDF();
pdf.addImage(canvas.toDataURL("image/png"), "PNG", 10, 10, 180, 100);
pdf.save("dashboard.pdf");
});
}

{{-- SIMPLE LIVE UPDATE --}}
setInterval(() => {
fetch(window.location.href)
.then(() => console.log("refreshed"));
}, 60000);

</script>

</x-app-layout>