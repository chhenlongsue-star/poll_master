<x-app-layout>
    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- TOP NAVIGATION --}}
            <div class="flex justify-between items-center mb-8">
                <a href="{{ route('dashboard') }}" class="text-[10px] font-black text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Feed
                </a>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Poll ID: #{{ $poll->id }}</span>
            </div>

            {{-- NOTIFICATIONS --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-500 text-white text-[11px] font-black uppercase tracking-widest px-6 py-4 rounded-2xl shadow-lg shadow-emerald-500/20 flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl overflow-hidden border border-transparent dark:border-gray-700">
                
                {{-- POLL HEADER --}}
                <div class="p-10 border-b dark:border-gray-700 bg-white dark:bg-gray-800">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="px-3 py-1 bg-indigo-600 text-white text-[9px] font-black rounded-lg uppercase tracking-widest shadow-lg shadow-indigo-500/20">
                            {{ $poll->category->name }}
                        </span>
                        @if($poll->is_active)
                            <span class="flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 text-emerald-500 text-[9px] font-black rounded-lg uppercase tracking-widest border border-emerald-500/20">
                                <span class="h-1 w-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                Live
                            </span>
                        @endif
                    </div>

                    <h1 class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic leading-none mb-4">
                        {{ $poll->title }}
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium leading-relaxed max-w-2xl">
                        {{ $poll->description }}
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-y-2 gap-x-6 text-[10px] font-black uppercase tracking-widest text-gray-400">
                        <div class="flex items-center gap-2">
                            <div class="h-5 w-5 rounded-md bg-gray-100 dark:bg-gray-900 flex items-center justify-center text-indigo-600 dark:text-indigo-400 border dark:border-gray-700">
                                {{ strtoupper(substr($poll->user->name, 0, 1)) }}
                            </div>
                            <span class="text-gray-900 dark:text-gray-200">{{ $poll->user->name }}</span>
                        </div>
                        <span class="text-gray-200 dark:text-gray-700 text-lg">•</span>
                        <span>{{ $poll->created_at->diffForHumans() }}</span>
                        <span class="text-gray-200 dark:text-gray-700 text-lg">•</span>
                        <span class="text-indigo-600 dark:text-indigo-400">{{ number_format($poll->votes_count) }} Total Votes</span>
                    </div>
                </div>

                {{-- POLL CONTENT --}}
                <div class="p-10 bg-gray-50/50 dark:bg-gray-900/30">
                    @if($userVote)
                        {{-- RESULTS VIEW --}}
                        <div class="space-y-8">
                            <div class="flex items-center gap-4 mb-2">
                                <h3 class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-[0.2em]">Live Statistics</h3>
                                <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
                            </div>
                            
                            @foreach($poll->options as $option)
                                @php
                                    $percentage = $poll->votes_count > 0 ? round(($option->votes->count() / $poll->votes_count) * 100) : 0;
                                    $isUserChoice = $userVote->option_id === $option->id;
                                @endphp
                                
                                <div class="relative">
                                    <div class="flex justify-between items-end mb-2">
                                        <span class="text-xs font-black uppercase tracking-tight {{ $isUserChoice ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300' }}">
                                            {{ $option->option_text }} 
                                            @if($isUserChoice) 
                                                <span class="ml-2 px-2 py-0.5 bg-indigo-600 text-white text-[8px] rounded uppercase">My Vote</span> 
                                            @endif
                                        </span>
                                        <span class="text-sm font-black text-gray-900 dark:text-white">{{ $percentage }}%</span>
                                    </div>
                                    
                                    {{-- PROGRESS BAR --}}
                                    <div class="w-full bg-gray-200 dark:bg-gray-800 rounded-xl h-3 overflow-hidden shadow-inner">
                                        <div class="h-full rounded-xl transition-all duration-1000 shadow-[0_0_15px_rgba(79,70,229,0.4)]
                                            {{ $isUserChoice ? 'bg-indigo-600' : 'bg-gray-400 dark:bg-gray-600' }}" 
                                            style="width: {{ $percentage }}%">
                                        </div>
                                    </div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase mt-2 tracking-widest">{{ number_format($option->votes->count()) }} Collective Votes</p>
                                </div>
                            @endforeach
                            
                            <div class="mt-12 p-6 bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 text-center">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">
                                    Your secure entry was recorded {{ $userVote->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                    @else
                        {{-- VOTING FORM --}}
                        <form action="{{ route('polls.vote', $poll) }}" method="POST">
                            @csrf
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-8 text-center">Select your response</h3>
                            
                            <div class="space-y-4">
                                @foreach($poll->options as $option)
                                    <label class="group relative flex items-center p-5 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-2xl cursor-pointer hover:border-indigo-500 dark:hover:border-indigo-500 transition-all duration-200 shadow-sm">
                                        <input type="radio" name="option_id" value="{{ $option->id }}" class="h-5 w-5 text-indigo-600 border-gray-300 focus:ring-offset-0 focus:ring-transparent bg-gray-50 dark:bg-gray-900" required>
                                        <span class="ml-4 text-sm font-black text-gray-700 dark:text-gray-300 uppercase tracking-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                            {{ $option->option_text }}
                                        </span>
                                        <div class="absolute inset-0 border-2 border-transparent group-has-[:checked]:border-indigo-600 rounded-2xl pointer-events-none"></div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="mt-12 text-center">
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 rounded-2xl uppercase text-xs tracking-[0.2em] transition-all shadow-xl shadow-indigo-500/20 transform hover:-translate-y-1">
                                    Secure My Vote
                                </button>
                                <div class="flex items-center justify-center gap-2 mt-6">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">One submission allowed per identity</p>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>