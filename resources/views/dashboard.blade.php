<x-app-layout>
    {{-- Dependencies --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <x-slot name="header">
        <div class="flex justify-between items-center px-4 sm:px-0">
            <h2 class="font-black text-2xl sm:text-3xl text-gray-900 dark:text-white uppercase tracking-tighter italic">
                Poll Master
            </h2>

            <a href="{{ route('polls.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 sm:px-6 sm:py-2.5 rounded-2xl text-[10px] sm:text-xs font-black uppercase tracking-widest shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1 flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> <span class="hidden xs:inline">Create Poll</span><span class="xs:hidden">New</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-10 bg-gray-100 dark:bg-gray-950 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

            {{-- RESPONSIVE SEARCH & FILTER BAR --}}
            <form action="{{ route('dashboard') }}" method="GET" 
                  class="bg-white dark:bg-gray-900 p-2 sm:p-3 rounded-2xl sm:rounded-[2.5rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-transparent dark:border-gray-800 flex flex-col md:flex-row gap-3">
                
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="SEARCH THE COLLECTIVE MINDSET..."
                           class="w-full border-none bg-gray-50 dark:bg-gray-800 rounded-xl sm:rounded-2xl pl-12 pr-4 py-3.5 sm:py-4 text-[10px] font-black uppercase tracking-[0.1em] focus:ring-2 focus:ring-indigo-500 dark:text-white transition-all">
                </div>

                <div class="flex gap-2">
                    <select name="category" 
                            class="flex-1 md:w-48 bg-gray-50 dark:bg-gray-800 border-none rounded-xl sm:rounded-2xl text-[10px] font-black uppercase tracking-widest px-4 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl sm:rounded-2xl text-[10px] font-black uppercase tracking-widest transition shadow-lg shadow-indigo-500/20 active:scale-95">
                        Filter
                    </button>
                </div>
            </form>

            {{-- TAB NAVIGATION --}}
            @php $currentTab = request('tab','all'); @endphp
            <div class="flex gap-6 sm:gap-8 border-b dark:border-gray-800 pb-1 overflow-x-auto no-scrollbar">
                @foreach(['all' => 'All', 'official' => 'Official', 'trending' => 'Trending', 'community' => 'Community', 'favorites' => 'Favorites'] as $key => $label)
                    <a href="?tab={{ $key }}" 
                       class="whitespace-nowrap pb-3 text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] transition-all {{ $currentTab == $key ? 'text-indigo-600 border-b-4 border-indigo-600' : 'text-gray-400 hover:text-gray-600 border-b-4 border-transparent' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- LOADER --}}
            <div id="loader" class="flex justify-center py-20">
                <div class="relative">
                    <div class="animate-spin w-10 h-10 border-[3px] border-indigo-500 border-t-transparent rounded-full"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>

            {{-- MAIN POLL GRID --}}
            <div id="content" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 hidden">
                @forelse($allPolls as $poll)
                    @php 
                        $hasVoted = $poll->votes()->where('user_id', Auth::id())->exists();
                    @endphp

                    <div class="group bg-white dark:bg-gray-800 rounded-[2rem] sm:rounded-[2.5rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-transparent dark:border-gray-700 hover:-translate-y-2 transition-all duration-500 flex flex-col overflow-hidden">
                        
                        {{-- Card Body --}}
                        <div class="p-6 sm:p-8 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-6">
                                <span class="px-2.5 py-1 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[8px] sm:text-[9px] font-black uppercase tracking-widest rounded-lg border border-indigo-100 dark:border-indigo-500/20">
                                    {{ $poll->category->name ?? 'General' }}
                                </span>

                                <div class="flex items-center gap-3">
                                    @if($hasVoted)
                                        <span class="flex items-center gap-1.5 text-[8px] sm:text-[9px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-100 dark:border-emerald-500/20">
                                            <i class="fas fa-check-double"></i> Voted
                                        </span>
                                    @endif

                                    <form action="{{ route('polls.favourite', $poll) }}" method="POST">
                                        @csrf
                                        <button class="hover:scale-125 transition-transform duration-300">
                                            @if(Auth::user()->favouritePolls->contains($poll->id))
                                                <i class="fas fa-heart text-red-500"></i>
                                            @else
                                                <i class="far fa-heart text-gray-300 dark:text-gray-600 hover:text-red-400"></i>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <h4 class="text-lg sm:text-xl font-black text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition italic uppercase tracking-tighter leading-tight mb-4">
                                {{ $poll->title }}
                            </h4>

                            <p class="text-[11px] sm:text-xs text-gray-500 dark:text-gray-400 font-medium line-clamp-2 mb-8">
                                {{ $poll->description }}
                            </p>

                            {{-- Options Preview --}}
                            <div class="space-y-2.5 mb-8">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.3em] mb-2">Preview</p>
                                @foreach($poll->options->take(3) as $option)
                                    <div class="flex items-center justify-between p-3.5 bg-gray-50 dark:bg-gray-900/50 rounded-xl sm:rounded-2xl border border-transparent dark:border-gray-700">
                                        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-tight text-gray-700 dark:text-gray-300 truncate pr-4">
                                            {{ $option->option_text }}
                                        </span>
                                        <div class="shrink-0 h-1.5 w-1.5 rounded-full {{ $hasVoted ? 'bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.6)]' : 'border border-gray-300 dark:border-gray-600' }}"></div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Card Footer --}}
                            <div class="flex justify-between items-center text-[8px] sm:text-[9px] font-black uppercase tracking-widest text-gray-400 mt-auto pt-4 border-t dark:border-gray-700/50">
                                <span class="flex items-center gap-2">
                                    <div class="w-1 h-1 bg-emerald-500 rounded-full animate-pulse"></div>
                                    {{ number_format($poll->votes_count) }} Global Votes
                                </span>
                                <span>{{ $poll->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="px-6 sm:px-8 pb-6 sm:pb-8">
                            <a href="{{ route('polls.show',$poll) }}"
                               class="block w-full text-center py-3.5 sm:py-4 rounded-xl sm:rounded-[1.25rem] text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] transition-all duration-300
                               {{ $hasVoted 
                                  ? 'bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700 border dark:border-gray-700' 
                                  : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-xl shadow-indigo-500/30' }}">
                                {{ $hasVoted ? 'View Results' : 'Cast Your Vote' }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <i class="fas fa-poll-h text-5xl text-gray-200 dark:text-gray-800 mb-4"></i>
                        <p class="text-gray-400 font-black uppercase tracking-widest text-[10px]">No polls found in the mindset.</p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="mt-12 sm:mt-16">
                {{ $allPolls->links() }}
            </div>

        </div>
    </div>

    {{-- INTERFACE SCRIPT --}}
    <script>
        window.addEventListener('load', () => {
            setTimeout(() => {
                const loader = document.getElementById('loader');
                const content = document.getElementById('content');
                
                if(loader) loader.style.display = 'none';
                if(content) {
                    content.classList.remove('hidden');
                    content.classList.add('animate-in', 'fade-in', 'duration-700');
                }
            }, 400);
        });
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>