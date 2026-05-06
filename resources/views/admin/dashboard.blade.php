<x-app-layout>

<div class="py-10 bg-gray-100 min-h-screen">
<div class="max-w-7xl mx-auto px-4 space-y-8">

<div class="grid md:grid-cols-4 gap-6">

<div class="bg-white p-6 rounded-xl shadow">
<p class="text-xs text-gray-400">Users</p>
<h3 class="text-2xl font-bold">{{ $totalUsers }}</h3>
</div>

<div class="bg-white p-6 rounded-xl shadow">
<p class="text-xs text-gray-400">Active</p>
<h3 class="text-2xl font-bold text-green-500">{{ $activeUsersCount }}</h3>
</div>

<div class="bg-white p-6 rounded-xl shadow">
<p class="text-xs text-gray-400">Votes</p>
<h3 class="text-2xl font-bold text-indigo-500">{{ $totalVotes }}</h3>
</div>

<div class="bg-white p-6 rounded-xl shadow">
<p class="text-xs text-gray-400">Polls</p>
<h3 class="text-2xl font-bold text-yellow-500">{{ $totalPolls }}</h3>
</div>

</div>

<div class="bg-white p-6 rounded-xl shadow">

<div class="flex justify-between mb-4">

<form>
<input id="range" name="range" class="border px-2 py-1 text-xs rounded">
<button class="bg-indigo-600 text-white px-3 py-1 text-xs rounded">Apply</button>
</form>

<button onclick="downloadPDF()" class="bg-green-600 text-white px-3 py-1 text-xs rounded">
Export
</button>

</div>

<canvas id="chart"></canvas>

</div>

<div class="grid md:grid-cols-3 gap-6">

<div class="bg-white p-4 rounded-xl shadow">
<h4 class="font-bold mb-3">Top Users</h4>
@foreach($topUsers as $u)
<div class="flex justify-between text-sm py-1">
<span>{{ $u->name }}</span>
<span>{{ $u->polls_count }}</span>
</div>
@endforeach
</div>

<div class="bg-white p-4 rounded-xl shadow">
<h4 class="font-bold mb-3">Top Polls</h4>
@foreach($topPolls as $p)
<div class="flex justify-between text-sm py-1">
<span>{{ $p->title }}</span>
<span>{{ $p->votes_count }}</span>
</div>
@endforeach
</div>

<div class="bg-white p-4 rounded-xl shadow">
<h4 class="font-bold mb-3">Recent Activity</h4>
@foreach($recentActivities as $a)
<div class="text-xs py-1">
User #{{ $a->user_id }} voted
</div>
@endforeach
</div>

</div>

<div class="bg-white rounded-xl shadow overflow-hidden">

<table class="min-w-full text-sm">
<thead class="bg-gray-50">
<tr>
<th class="px-4 py-2 text-left">User</th>
<th class="px-4 py-2">Role</th>
<th class="px-4 py-2 text-right">Action</th>
</tr>
</thead>

<tbody>
@foreach($users as $user)
<tr class="border-t">

<td class="px-4 py-2">{{ $user->name }}</td>

<td class="px-4 py-2 text-center">

<form method="POST" action="{{ route('admin.users.update-role',$user) }}">
@csrf
@method('PATCH')

<select name="role" onchange="this.form.submit()" class="border text-xs rounded px-2">
<option value="user" {{ $user->role=='user'?'selected':'' }}>User</option>
<option value="sub_admin" {{ $user->role=='sub_admin'?'selected':'' }}>Sub Admin</option>
@if(auth()->user()->role==='admin')
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
<button class="text-xs {{ $user->is_banned?'text-green-500':'text-orange-500' }}">
{{ $user->is_banned?'Unblock':'Block' }}
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas"></script>

<script>
flatpickr("#range",{mode:"range",dateFormat:"Y-m-d"});

new Chart(document.getElementById('chart'),{
type:'line',
data:{
labels:{!! json_encode($dates) !!},
datasets:[
{label:'Votes',data:{!! json_encode($votes) !!},borderColor:'#4F46E5'},
{label:'Users',data:{!! json_encode($usersData) !!},borderColor:'#10B981'},
{label:'Polls',data:{!! json_encode($pollsData) !!},borderColor:'#F59E0B'}
]
}
});

function downloadPDF(){
html2canvas(document.getElementById('chart')).then(canvas=>{
const pdf=new jspdf.jsPDF();
pdf.addImage(canvas.toDataURL('image/png'),'PNG',10,10,180,100);
pdf.save("analytics.pdf");
});
}
</script>

</x-app-layout>