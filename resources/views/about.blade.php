<x-app-layout>
    <div class="py-12 bg-gray-100 dark:bg-gray-950 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Header Section --}}
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-2xl rounded-[2.5rem] border border-transparent dark:border-gray-800 p-10">
                <h1 class="text-4xl font-black italic uppercase tracking-tighter text-gray-900 dark:text-white mb-4">
                    About PollMaster
                </h1>
                <p class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest text-xs leading-relaxed max-w-2xl">
                    PollMaster is a high-performance voting platform built for the collective mindset. We bridge the gap between curiosity and data.
                </p>
            </div>

            {{-- Tech Stack Section --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-8 bg-white dark:bg-gray-900 rounded-[2.5rem] border border-transparent dark:border-gray-800 text-center">
                    <div class="text-indigo-500 mb-4 flex justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    </div>
                    <h3 class="text-white font-black uppercase tracking-widest text-[10px] mb-2">Development</h3>
                    <p class="text-gray-400 text-xs font-bold uppercase">GitHub & Laravel</p>
                </div>

                <div class="p-8 bg-white dark:bg-gray-900 rounded-[2.5rem] border border-transparent dark:border-gray-800 text-center">
                    <div class="text-indigo-500 mb-4 flex justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7C5 4 4 5 4 7z" /></svg>
                    </div>
                    <h3 class="text-white font-black uppercase tracking-widest text-[10px] mb-2">Infrastructure</h3>
                    <p class="text-gray-400 text-xs font-bold uppercase">Render & Neon DB</p>
                </div>

                <div class="p-8 bg-white dark:bg-gray-900 rounded-[2.5rem] border border-transparent dark:border-gray-800 text-center">
                    <div class="text-indigo-500 mb-4 flex justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="text-white font-black uppercase tracking-widest text-[10px] mb-2">Security</h3>
                    <p class="text-gray-400 text-xs font-bold uppercase">Google OAuth 2.0</p>
                </div>
            </div>

            {{-- Contact Section --}}
            <div class="bg-indigo-600 rounded-[2.5rem] p-10 text-center shadow-xl shadow-indigo-500/20">
                <h2 class="text-white font-black italic uppercase tracking-tighter text-2xl mb-4">Have questions?</h2>
                <p class="text-indigo-100 font-bold uppercase tracking-widest text-[10px] mb-8">Reach out to the developer directly</p>
                
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=chhenlonglok@gmail.com" 
                   target="_blank"
                   class="inline-flex items-center gap-3 bg-white text-indigo-600 px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-gray-100 transition-all transform active:scale-95">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    Contact via Gmail
                </a>
            </div>

        </div>
    </div>
</x-app-layout>