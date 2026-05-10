{{-- 1. Overlay / Backdrop --}}
<div x-show="sidebarOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-40">
</div>

{{-- 2. Sidebar Panel --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 w-72 bg-white dark:bg-gray-800 shadow-2xl z-50 transform transition-transform duration-300 ease-in-out border-r dark:border-gray-700 overflow-y-auto flex flex-col">
    
    {{-- Brand Section --}}
    <div class="p-8 flex items-center justify-between">
        <h2 class="font-black text-2xl italic tracking-tighter text-indigo-600 dark:text-indigo-400">
            POLL MASTER
        </h2>
        <button @click="sidebarOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 space-y-1.5">
        
        {{-- USER SECTION --}}
        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] px-4 mb-2">Main Menu</p>
        
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-gray-500 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-gray-700/50 hover:text-indigo-600' }}">
            {{-- <i class="fas fa-th-large w-5 text-sm"></i> --}}
            <span class="text-xs font-black uppercase tracking-widest">Dashboard</span>
        </a>

        <a href="{{ route('polls.create') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('polls.create') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-gray-500 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-gray-700/50 hover:text-indigo-600' }}">
            {{-- <i class="fas fa-plus-circle w-5 text-sm"></i> --}}
            <span class="text-xs font-black uppercase tracking-widest">Create Poll</span>
        </a>

        <a href="{{ route('polls.my-content') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('polls.my-content') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-gray-500 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-gray-700/50 hover:text-indigo-600' }}">
            {{-- <i class="fas fa-folder w-5 text-sm"></i> --}}
            <span class="text-xs font-black uppercase tracking-widest">My Content</span>
        </a>

        <a href="{{ route('about') }}" 
           class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('about') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-gray-500 dark:text-gray-400 hover:bg-indigo-50 dark:hover:bg-gray-700/50 hover:text-indigo-600' }}">
            {{-- <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" 
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg> --}}
            <span class="text-xs font-black uppercase tracking-widest">About Us</span>
        </a>


        {{-- ADMIN SECTION --}}
        @if(in_array(auth()->user()->role,['admin','sub_admin']))
            <div class="pt-6 pb-2">
                <div class="h-px bg-gray-100 dark:bg-gray-700 mx-4"></div>
            </div>
            
            <p class="text-[9px] font-black text-red-500 dark:text-red-400 uppercase tracking-[0.2em] px-4 mb-2">Management</p>

            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-red-600' }}">
                {{-- <i class="fas fa-shield-alt w-5 text-sm"></i> --}}
                <span class="text-xs font-black uppercase tracking-widest">Admin Panel</span>
            </a>

            <a href="{{ route('admin.polls.index') }}" 
               class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.polls.index') ? 'bg-red-600 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-red-600' }}">
                {{-- <i class="fas fa-tasks w-5 text-sm"></i> --}}
                <span class="text-xs font-black uppercase tracking-widest">Manage Polls</span>
            </a>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.categories.index') ? 'bg-red-600 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-red-600' }}">
                    {{-- <i class="fas fa-tags w-5 text-sm"></i> --}}
                    <span class="text-xs font-black uppercase tracking-widest">Categories</span>
                </a>
            @endif
        @endif
    </nav>

    {{-- Bottom Footer Section --}}
    <div class="p-6 border-t dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-4 px-4 py-3 rounded-xl text-gray-400 hover:text-red-500 transition-all font-black text-[10px] uppercase tracking-[0.2em]">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </form>
    </div>
</aside>