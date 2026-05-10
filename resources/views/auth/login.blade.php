<x-guest-layout>
    {{-- Main Page Wrapper to fix the white background issue --}}
    <div class="min-h-screen w-full flex flex-col sm:justify-center items-center bg-gray-100 dark:bg-gray-950 transition-colors duration-300 px-4">
        
        {{-- Brand / Logo Section --}}
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">
                PollMaster
            </h2>
            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.3em] mt-2">
                The Collective Mindset
            </p>
        </div>

        {{-- Login Card --}}
        <div class="w-full sm:max-w-md bg-white dark:bg-gray-900 p-8 sm:p-10 shadow-2xl shadow-gray-200/50 dark:shadow-none rounded-[2.5rem] border border-transparent dark:border-gray-800 transition-all">
            
            <div class="mb-8 text-center">
                <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">
                    Welcome Back
                </h3>
                <p class="text-[9px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-1">
                    Log in to continue
                </p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                {{-- Email Field --}}
                <div>
                    <label for="email" class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2 ml-1">Email Address</label>
                    <input id="email" 
                           class="block w-full px-5 py-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 transition shadow-sm text-sm font-bold" 
                           type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                           placeholder="admin@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Password Field --}}
                <div>
                    <label for="password" class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-2 ml-1">Password</label>
                    <input id="password" 
                           class="block w-full px-5 py-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 transition shadow-sm text-sm font-bold" 
                           type="password" name="password" required autocomplete="current-password" 
                           placeholder="••••••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Options --}}
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 transition cursor-pointer" name="remember">
                        <span class="ms-3 text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest group-hover:text-gray-600 dark:group-hover:text-gray-300 transition">
                            Stay Signed In
                        </span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-500/30 transition-all transform active:scale-95">
                    Login
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative flex items-center justify-center my-10">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-100 dark:border-gray-800"></div>
                </div>
                <div class="relative px-4 text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.3em] bg-white dark:bg-gray-900">
                    Social Access
                </div>
            </div>

            {{-- Google Login --}}
            <div>
                <a href="{{ route('auth.google') }}" 
                   class="flex items-center justify-center w-full px-4 py-4 text-[10px] font-black text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 border border-transparent hover:border-gray-200 dark:hover:border-gray-700 rounded-2xl shadow-sm hover:bg-white dark:hover:bg-gray-700 transition-all uppercase tracking-widest group">
                    <img class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google logo">
                    <span>Continue with Google</span>
                </a>
            </div>
        </div>

        {{-- Footer Link --}}
        <div class="mt-8 text-center">
            <p class="text-[9px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-[0.2em]">
                &copy; 2026 PollMaster Collective
            </p>
        </div>
    </div>
</x-guest-layout>