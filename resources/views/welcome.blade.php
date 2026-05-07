<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Poll Master | Create & Vote</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600,900&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-white dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300">
        
        {{-- NAVIGATION --}}
        <nav class="sticky top-0 z-50 bg-white/80 dark:bg-gray-950/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400 italic uppercase tracking-tighter">Poll Master</span>
                    </div>
                    <div class="flex items-center space-x-8">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-indigo-600 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-indigo-600 transition">Log in</a>
                                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20">Sign Up</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <main>
            {{-- HERO SECTION --}}
            <div class="relative pt-20 pb-32 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:grid lg:grid-cols-12 lg:gap-12 items-center">
                        <div class="sm:text-center lg:col-span-6 lg:text-left">
                            <h1 class="text-5xl sm:text-7xl font-black tracking-tighter uppercase italic leading-[0.9]">
                                <span class="block text-gray-900 dark:text-white">Collect</span>
                                <span class="block text-indigo-600">Opinions</span>
                                <span class="block text-gray-900 dark:text-white text-3xl sm:text-4xl mt-2 italic tracking-normal font-normal">Fast, Secure, Real-time.</span>
                            </h1>
                            <p class="mt-6 text-lg text-gray-500 dark:text-gray-400 font-medium max-w-lg leading-relaxed">
                                Create interactive polls in seconds and visualize the data as it happens. Built for communities that value clear, spam-free feedback.
                            </p>
                            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                <a href="{{ route('register') }}" class="px-10 py-5 bg-indigo-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-indigo-700 transition shadow-2xl shadow-indigo-500/40 transform hover:-translate-y-1">
                                    Get Started
                                </a>
                                <a href="#trending" class="px-10 py-5 bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-300 text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-800 transition">
                                    Explore Polls
                                </a>
                            </div>
                        </div>

                        {{-- ABSTRACT MOCKUP --}}
                        <div class="mt-16 lg:mt-0 lg:col-span-6">
                            <div class="relative">
                                <div class="absolute -inset-4 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-[3rem] blur-3xl"></div>
                                <div class="relative bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-8 rounded-[2.5rem] shadow-2xl">
                                    <div class="space-y-6">
                                        <div class="h-12 w-3/4 bg-gray-50 dark:bg-gray-800 rounded-2xl animate-pulse"></div>
                                        <div class="space-y-3">
                                            <div class="h-16 w-full border-2 border-indigo-500/30 rounded-2xl flex items-center px-6">
                                                <div class="h-3 w-3 rounded-full bg-indigo-500 mr-4"></div>
                                                <div class="h-3 w-32 bg-gray-100 dark:bg-gray-800 rounded-full"></div>
                                            </div>
                                            <div class="h-16 w-full border-2 border-transparent bg-gray-50 dark:bg-gray-800/50 rounded-2xl flex items-center px-6">
                                                <div class="h-3 w-3 rounded-full border-2 border-gray-300 dark:border-gray-600 mr-4"></div>
                                                <div class="h-3 w-24 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TRENDING SECTION --}}
            <div id="trending" class="bg-gray-50 dark:bg-gray-900/50 py-24 border-t border-gray-100 dark:border-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-4">
                        <div>
                            <h2 class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Trending Now</h2>
                            <p class="text-[10px] text-indigo-600 dark:text-indigo-400 font-black uppercase tracking-[0.3em] mt-2">Active decisions in the community</p>
                        </div>
                        <a href="{{ route('login') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-indigo-600 transition">View All <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @forelse($trendingPolls as $poll)
                            <div class="group bg-white dark:bg-gray-800 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-transparent dark:border-gray-700 overflow-hidden hover:-translate-y-2 transition-all duration-300">
                                <div class="p-8">
                                    <div class="flex justify-between items-center mb-6">
                                        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-900 text-[9px] font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 rounded-lg">
                                            {{ $poll->category->name ?? 'General' }}
                                        </span>
                                        <div class="flex items-center gap-1.5">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ number_format($poll->votes_count) }} Votes</span>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-4 uppercase tracking-tighter italic leading-tight group-hover:text-indigo-600 transition">{{ $poll->title }}</h3>
                                    
                                    <div class="space-y-2">
                                        @foreach($poll->options->take(2) as $option)
                                            <div class="w-full bg-gray-50 dark:bg-gray-900/50 rounded-xl p-3 text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-tight flex justify-between border border-transparent dark:border-gray-800">
                                                <span>{{ Str::limit($option->option_text, 25) }}</span>
                                                <span class="text-indigo-500 opacity-0 group-hover:opacity-100 transition">○</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="px-8 py-5 bg-gray-50 dark:bg-gray-900/80 border-t border-gray-100 dark:border-gray-800">
                                    <a href="{{ route('login') }}" class="block text-center w-full text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">
                                        Join Controversy →
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-20 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-[2rem]">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No pulse detected. Start the first poll.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- FEATURES --}}
            <div class="bg-white dark:bg-gray-950 py-32">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-12 sm:grid-cols-3">
                        <div class="relative p-8 bg-indigo-50 dark:bg-gray-900 rounded-[2rem] border border-transparent dark:border-gray-800 group hover:border-indigo-500 transition-colors">
                            <div class="text-4xl mb-6 transform group-hover:scale-110 transition">⚡</div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Instant Launch</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium leading-relaxed">Questions to answers in 30 seconds. No bloated setup forms.</p>
                        </div>
                        <div class="relative p-8 bg-gray-50 dark:bg-gray-900 rounded-[2rem] border border-transparent dark:border-gray-800 group hover:border-indigo-500 transition-colors">
                            <div class="text-4xl mb-6 transform group-hover:scale-110 transition">📊</div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Dynamic Feed</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium leading-relaxed">Watch the consensus shift in real-time with automated data viz.</p>
                        </div>
                        <div class="relative p-8 bg-gray-50 dark:bg-gray-900 rounded-[2rem] border border-transparent dark:border-gray-800 group hover:border-indigo-500 transition-colors">
                            <div class="text-4xl mb-6 transform group-hover:scale-110 transition">🔒</div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Integrity First</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium leading-relaxed">Unique session tracking prevents bot spam and double voting.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-white dark:bg-gray-950 border-t border-gray-100 dark:border-gray-800 py-16">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <div class="text-2xl font-black text-gray-300 dark:text-gray-800 uppercase italic tracking-tighter mb-6">Poll Master</div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-600 uppercase tracking-widest">&copy; 2026 Engine Core. Built with Laravel 12 & Tailwind CSS.</p>
            </div>
        </footer>
    </body>
</html>