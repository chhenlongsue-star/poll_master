<section class="bg-white dark:bg-gray-900 p-8 rounded-[2rem] border border-transparent dark:border-gray-800 transition-colors duration-300 shadow-sm">
    <header>
        <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest italic">
            Profile Information
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            View your account details and profile picture synced from Google.
        </p>
    </header>

    <div class="mt-8 space-y-8">
        <div class="flex items-center space-x-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-transparent dark:border-gray-700/50">
            <div class="shrink-0 relative">
                <img class="h-24 w-24 object-cover rounded-full border-4 border-white dark:border-gray-700 shadow-xl" 
                     src="{{ Auth::user()->avatar ?? Auth::user()->google_avatar ?? asset('images/default-avatar.png') }}" 
                     alt="Profile Photo" 
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff'"/>
                
                {{-- Status Indicator --}}
                <div class="absolute bottom-1 right-1 w-5 h-5 bg-emerald-500 border-4 border-white dark:border-gray-800 rounded-full"></div>
            </div>
            <div>
                <span class="block text-sm font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-tighter">
                    <i class="fab fa-google mr-1"></i> Syncing from Google
                </span>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Your profile picture is managed by your Google Account.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Name Field --}}
            <div class="space-y-2">
                <label class="block font-black text-gray-700 dark:text-gray-400 text-[10px] uppercase tracking-[0.2em] ml-1">Full Name</label>
                <div class="p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-gray-100 font-bold text-sm shadow-inner italic">
                    {{ Auth::user()->name }}
                </div>
            </div>

            {{-- Email Field --}}
            <div class="space-y-2">
                <label class="block font-black text-gray-700 dark:text-gray-400 text-[10px] uppercase tracking-[0.2em] ml-1">Email Address</label>
                <div class="p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-gray-100 font-bold text-sm shadow-inner">
                    {{ Auth::user()->email }}
                </div>
            </div>
        </div>

        {{-- Account Role Section --}}
        <div class="pt-4">
            <label class="block font-black text-gray-700 dark:text-gray-400 text-[10px] uppercase tracking-[0.2em] ml-1 mb-3">Privilege Level</label>
            <div class="inline-flex items-center gap-3 px-6 py-2.5 rounded-2xl text-xs font-black uppercase tracking-widest bg-indigo-600 text-white shadow-lg shadow-indigo-500/20">
                <i class="fas fa-shield-alt"></i>
                {{ Auth::user()->role }}
            </div>
        </div>
    </div>
</section>