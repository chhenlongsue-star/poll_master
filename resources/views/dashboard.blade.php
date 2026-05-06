<x-app-layout>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<x-slot name="header">
<div class="flex justify-between items-center">
<h2 class="font-bold text-2xl text-gray-800">Poll Master</h2>

<a href="{{ route('polls.create') }}"
class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl text-sm font-semibold shadow flex items-center">
<i class="fas fa-plus mr-2"></i> Create Poll
</a>
</div>
</x-slot>

<div class="py-10 bg-gray-50 min-h-screen">
<div class="max-w-7xl mx-auto px-4 space-y-8">

<div class="bg-white p-4 rounded-xl shadow-sm border flex gap-3">

<input type="text" name="search" value="{{ request('search') }}"
placeholder="Search polls..."
class="flex-1 border rounded-lg px-3 py-2 text-sm">

<select name="category" class="border rounded-lg text-sm px-2">
<option value="">All</option>
@foreach($categories as $category)
<option value="{{ $category->id }}">{{ $category->name }}</option>
@endforeach
</select>

<button class="bg-indigo-600 text-white px-4 rounded-lg text-sm">Search</button>

</div>

@php $currentTab = request('tab','all'); @endphp

<div class="flex gap-6 border-b pb-2 text-sm font-semibold">

<a href="?tab=all" class="{{ $currentTab=='all'?'text-indigo-600':'' }}">All</a>
<a href="?tab=official" class="{{ $currentTab=='official'?'text-indigo-600':'' }}">Official</a>
<a href="?tab=trending" class="{{ $currentTab=='trending'?'text-indigo-600':'' }}">🔥 Trending</a>
<a href="?tab=community" class="{{ $currentTab=='community'?'text-indigo-600':'' }}">Community</a>
<a href="?tab=favorites" class="{{ $currentTab=='favorites'?'text-indigo-600':'' }}">⭐ Favorites</a>

</div>

<div id="loader" class="flex justify-center py-10">
<div class="animate-spin w-8 h-8 border-2 border-indigo-500 border-t-transparent rounded-full"></div>
</div>

<div id="content" class="hidden grid md:grid-cols-2 lg:grid-cols-3 gap-6">

@foreach($allPolls as $poll)
<div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition group p-5">

<div class="flex justify-between mb-2">

<span class="text-xs bg-indigo-100 text-indigo-600 px-2 py-1 rounded">
{{ $poll->category->name ?? 'General' }}
</span>

<form action="{{ route('polls.favourite',$poll) }}" method="POST">
@csrf
<button>
@if(Auth::user()->favouritePolls()->where('poll_id',$poll->id)->exists())
<i class="fas fa-heart text-red-500"></i>
@else
<i class="far fa-heart text-gray-300 hover:text-red-400"></i>
@endif
</button>
</form>

</div>

<h4 class="font-bold text-gray-800 group-hover:text-indigo-600">
{{ $poll->title }}
</h4>

<p class="text-sm text-gray-500 mt-2 line-clamp-2">
{{ $poll->description }}
</p>

<div class="flex justify-between items-center mt-4 text-xs">

<span>{{ $poll->votes_count }} votes</span>

<span class="text-gray-400">
{{ $poll->created_at->diffForHumans() }}
</span>

</div>

<a href="{{ route('polls.show',$poll) }}"
class="block mt-4 text-center bg-gray-900 text-white py-2 rounded-lg hover:bg-indigo-600 text-sm">
Open
</a>

</div>
@endforeach

</div>

<div class="mt-6">
{{ $allPolls->links() }}
</div>

</div>
</div>

<script>
setTimeout(()=>{
document.getElementById('loader').style.display='none';
document.getElementById('content').classList.remove('hidden');
},400);
</script>

</x-app-layout>