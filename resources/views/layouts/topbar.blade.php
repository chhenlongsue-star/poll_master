<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <div class="flex items-center">
                <h2 class="text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                    {{ config('app.name') }} / Admin
                </h2>
            </div>

            <div class="flex items-center space-x-6">
                
                {{-- User Info (Visible on Desktop) --}}
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-gray-900 dark:text-white leading-none">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-[10px] text-indigo-500 font-black uppercase tracking-widest mt-1">
                        {{ Auth::user()->role }}
                    </p>
                </div>

                {{-- Profile Picture --}}
                <div class="relative group">
                    <a href="{{ route('profile.edit') }}" class="block">
                        <img src="{{ Auth::user()->avatar }}" 
                            class="h-9 w-9 rounded-full border-2 border-indigo-500 shadow-sm transition group-hover:scale-110 group-hover:shadow-indigo-500/50"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff'">
        
                        <div class="absolute inset-0 rounded-full border-2 border-transparent group-hover:border-indigo-400 transition-all duration-300"></div>
                    </a>
                </div>

                {{-- Logout Button --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-2 px-3 py-1.5 text-xs font-black text-red-500 border border-red-500 rounded-lg hover:bg-red-500 hover:text-white transition uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-6 0v-1m6-11V5a3 3 0 01-6 0v1" />
                        </svg>
                        Logout
                    </button>
                </form>

            </div>
        </div>
    </div>
</nav>