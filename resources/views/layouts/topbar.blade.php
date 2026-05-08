<nav class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 sticky top-0 z-30 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- LEFT SIDE: HAMBURGER & LOGO --}}
            <div class="flex items-center gap-2 sm:gap-4">
                {{-- Hamburger (Design stays sleek on all devices) --}}
                <button @click="sidebarOpen = true" 
                        class="text-gray-500 dark:text-gray-400 hover:text-indigo-500 transition-colors p-2 -ml-2"
                        aria-label="Open Sidebar">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 8h16M4 16h12" />
                    </svg>
                </button>

                {{-- Clickable Logo (Font size adjusts for mobile) --}}
                <a href="{{ route('dashboard') }}" class="flex items-center hover:opacity-80 transition group shrink-0">
                    <h2 class="text-[11px] sm:text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-[0.15em] sm:tracking-[0.2em] group-hover:text-indigo-500 transition">
                        <span class="text-indigo-600 dark:text-indigo-500">PollMaster</span> 
                        <span class="hidden xs:inline">/ Admin</span>
                    </h2>
                </a>
            </div>

            {{-- RIGHT SIDE: USER PROFILE & LOGOUT --}}
            <div class="flex items-center gap-3 sm:space-x-6">
                
                {{-- User Name (HIDDEN ON MOBILE to save space) --}}
                <div class="text-right hidden md:block">
                    <p class="text-xs font-bold text-gray-900 dark:text-white leading-none">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-[9px] text-indigo-500 font-black uppercase tracking-widest mt-1">
                        {{ Auth::user()->role }}
                    </p>
                </div>

                {{-- Profile Picture (Visible on all devices) --}}
                <div class="relative group shrink-0">
                    <a href="{{ route('profile.edit') }}" class="block">
                        <img src="{{ Auth::user()->avatar }}" 
                            class="h-8 w-8 sm:h-9 sm:w-9 rounded-full border-2 border-indigo-500 shadow-sm transition group-hover:scale-110 group-hover:shadow-indigo-500/50"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff'">
        
                        <div class="absolute inset-0 rounded-full border-2 border-transparent group-hover:border-indigo-400 transition-all duration-300"></div>
                    </a>
                </div>

                {{-- Logout (Icon only on mobile, Text + Icon on desktop) --}}
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-2 px-2 py-1.5 sm:px-3 sm:py-1.5 text-[10px] font-black text-red-500 border border-red-500/30 sm:border-red-500 rounded-lg hover:bg-red-500 hover:text-white transition uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-6 0v-1m6-11V5a3 3 0 01-6 0v1" />
                        </svg>
                        <span class="hidden lg:inline">Logout</span>
                    </button>
                </form>

            </div>
        </div>
    </div>
</nav>