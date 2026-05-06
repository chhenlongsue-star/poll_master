<nav x-data="{ open:false, adminMenu:false }" class="bg-white shadow-sm border-b">

<div class="max-w-7xl mx-auto px-4">

<div class="flex justify-between h-16 items-center">

<div class="flex items-center gap-8">

<a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
PollMaster
</a>

<div class="hidden md:flex items-center gap-6 text-sm font-medium">

<a href="{{ route('dashboard') }}"
class="{{ request()->routeIs('dashboard') ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">
Dashboard
</a>

<a href="{{ route('polls.create') }}"
class="text-gray-500 hover:text-indigo-600">
Create
</a>

<a href="{{ route('polls.my-content') }}"
class="text-gray-500 hover:text-indigo-600">
My Content
</a>

</div>

@if(in_array(auth()->user()->role,['admin','sub_admin']))
<div class="relative">

<button @click="adminMenu = !adminMenu"
class="text-sm font-semibold text-gray-600 hover:text-indigo-600">
Admin ▾
</button>

<div x-show="adminMenu" @click.outside="adminMenu=false"
class="absolute mt-2 w-44 bg-white border rounded-lg shadow-md z-50">

<a href="{{ route('admin.dashboard') }}"
class="block px-4 py-2 text-sm hover:bg-gray-100">
Dashboard
</a>

<a href="{{ route('admin.polls.index') }}"
class="block px-4 py-2 text-sm hover:bg-gray-100">
Manage Polls
</a>

@if(auth()->user()->role==='admin')
<a href="{{ route('admin.categories.index') }}"
class="block px-4 py-2 text-sm hover:bg-gray-100">
Categories
</a>
@endif

</div>

</div>
@endif

</div>

<div class="hidden md:flex items-center gap-4">

<div class="text-right">
<p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
<p class="text-[10px] uppercase text-indigo-500 font-bold">{{ auth()->user()->role }}</p>
</div>

<div class="relative">

<button @click="open = !open" class="flex items-center">

@if(auth()->user()->avatar)
<img src="{{ auth()->user()->avatar }}" class="h-9 w-9 rounded-full border-2 border-indigo-500">
@else
<div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
{{ strtoupper(substr(auth()->user()->name,0,1)) }}
</div>
@endif

</button>

<div x-show="open" @click.outside="open=false"
class="absolute right-0 mt-2 w-44 bg-white border rounded-lg shadow-md">

<a href="{{ route('profile.edit') }}"
class="block px-4 py-2 text-sm hover:bg-gray-100">
Profile
</a>

<form method="POST" action="{{ route('logout') }}">
@csrf
<button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
Logout
</button>
</form>

</div>

</div>

</div>

<div class="md:hidden flex items-center">
<button @click="open=!open">
☰
</button>
</div>

</div>

</div>

<div x-show="open" class="md:hidden px-4 pb-4">

<a href="{{ route('dashboard') }}" class="block py-2">Dashboard</a>
<a href="{{ route('polls.create') }}" class="block py-2">Create</a>
<a href="{{ route('polls.my-content') }}" class="block py-2">My Content</a>

@if(in_array(auth()->user()->role,['admin','sub_admin']))
<a href="{{ route('admin.dashboard') }}" class="block py-2 text-indigo-600 font-bold">Admin Panel</a>
@endif

</div>

</nav>