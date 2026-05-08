<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">
            Welcome Back
        </h2>
        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-2">
            {{ __('Log in to the collective mindset.') }}
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email Field --}}
        <div>
            <label for="email" class="block text-[10px] font-black text-gray-700 dark:text-gray-400 uppercase tracking-[0.2em] mb-1.5 ml-1">Email Address</label>
            <input id="email" 
                   class="block w-full px-4 py-3 rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm text-sm font-medium" 
                   type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password Field --}}
        <div>
            <label for="password" class="block text-[10px] font-black text-gray-700 dark:text-gray-400 uppercase tracking-[0.2em] mb-1.5 ml-1">Password</label>
            <input id="password" 
                   class="block w-full px-4 py-3 rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm text-sm font-medium" 
                   type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-indigo-600 shadow-sm focus:ring-indigo-500 transition cursor-pointer" name="remember">
                <span class="ms-2 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest group-hover:text-gray-700 dark:group-hover:text-gray-200 transition">
                    {{ __('Stay Signed In') }}
                </span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest hover:underline" href="{{ route('password.request') }}">
                    {{ __('Forgot?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1">
            {{ __('Authenticate') }}
        </button>
    </form>

    {{-- Divider --}}
    <div class="relative flex items-center justify-center my-8">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200 dark:border-gray-800"></div>
        </div>
        <div class="relative px-4 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.3em] bg-white dark:bg-gray-900">
            Social Access
        </div>
    </div>

    {{-- Google Login --}}
    <div class="mt-6">
        <a href="{{ route('auth.google') }}" 
           class="flex items-center justify-center w-full px-4 py-4 text-xs font-black text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition uppercase tracking-widest group">
            <img class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google logo">
            <span>Continue with Google</span>
        </a>
    </div>

</x-guest-layout>