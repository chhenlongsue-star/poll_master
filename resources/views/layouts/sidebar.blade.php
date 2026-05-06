<aside class="w-64 bg-white dark:bg-gray-800 border-r dark:border-gray-700">

<div class="p-6 text-xl font-bold text-indigo-600">
PollMaster
</div>

<nav class="space-y-2 px-4">

<a href="{{ route('dashboard') }}"
class="block px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-700">
Dashboard
</a>

<a href="{{ route('polls.create') }}"
class="block px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-700">
Create Poll
</a>

<a href="{{ route('polls.my-content') }}"
class="block px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-700">
My Content
</a>

@if(in_array(auth()->user()->role,['admin','sub_admin']))

<p class="text-xs text-gray-400 px-4 mt-4">ADMIN</p>

<a href="{{ route('admin.dashboard') }}"
class="block px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-700">
Admin Dashboard
</a>

<a href="{{ route('admin.polls.index') }}"
class="block px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-700">
Manage Polls
</a>

@if(auth()->user()->role === 'admin')
<a href="{{ route('admin.categories.index') }}"
class="block px-4 py-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-700">
Categories
</a>
@endif

@endif

</nav>

</aside>