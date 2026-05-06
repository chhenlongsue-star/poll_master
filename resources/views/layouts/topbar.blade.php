<div class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 px-6 py-3 flex justify-between items-center">

<div class="flex items-center gap-4">

<input type="text" placeholder="Search..."
class="px-3 py-2 text-sm rounded bg-gray-100 dark:bg-gray-700 focus:outline-none">

</div>

<div class="flex items-center gap-6">

<button onclick="toggleDark()" class="text-xl">
🌙
</button>

<div class="relative">

<button class="text-xl">
🔔
</button>

<span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1 rounded-full">
3
</span>

</div>

<div class="flex items-center gap-2">

@if(auth()->user()->avatar)
<img src="{{ auth()->user()->avatar }}" class="h-8 w-8 rounded-full">
@else
<div class="h-8 w-8 bg-indigo-500 rounded-full flex items-center justify-center text-white">
{{ strtoupper(substr(auth()->user()->name,0,1)) }}
</div>
@endif

<div class="text-sm">
<p>{{ auth()->user()->name }}</p>
<p class="text-xs text-indigo-500">{{ auth()->user()->role }}</p>
</div>

</div>

</div>

</div>