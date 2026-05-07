<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-3xl text-gray-900 dark:text-white uppercase tracking-tighter italic">
                Poll Master
            </h2>

            <a href="{{ route('polls.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1 flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> Create Poll
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-100 dark:bg-gray-950 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- MODERN SEARCH BAR --}}
            <form action="{{ route('dashboard') }}" method="GET" class="bg-white dark:bg-gray-900 p-3 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-transparent dark:border-gray-800 flex flex-wrap md:flex-nowrap gap-3">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search the collective mindset..."
                           class="w-full border-none bg-gray-50 dark:bg-gray-800 rounded-xl pl-10 pr-4 py-3 text-xs font-bold uppercase tracking-tight focus:ring-2 focus:ring-indigo-500 dark:text-white">
                </div>

                <select name="category" class="bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-xs font-black uppercase tracking-widest px-4 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="bg-gray-900 dark:bg-indigo-600 text-white px-8 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-600 transition shadow-lg">Filter</button>
            </form>

            {{-- TAB NAVIGATION --}}
            @php $currentTab = request('tab','all'); @endphp
            <div class="flex gap-8 border-b dark:border-gray-800 pb-2 overflow-x-auto no-scrollbar">
                @foreach(['all' => 'All', 'official' => 'Official', 'trending' => '🔥 Trending', 'community' => 'Community', 'favorites' => '⭐ Favorites'] as $key => $label)
                    <a href="?tab={{ $key }}" 
                       class="whitespace-nowrap pb-3 text-[10px] font-black uppercase tracking-[0.2em] transition-all {{ $currentTab == $key ? 'text-indigo-600 border-b-4 border-indigo-600' : 'text-gray-400 hover:text-gray-600 border-b-4 border-transparent' }}">
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

            {{-- POLL GRID --}}
            <div id="content" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($allPolls as $poll)
                    @php 
                        // Logic to check if user has voted in this specific poll
                        $hasVoted = $poll->votes()->where('user_id', Auth::id())->exists();
                    @endphp

                    <div class="group bg-white dark:bg-gray-800 rounded-[2rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-transparent dark:border-gray-700 hover:-translate-y-2 transition-all duration-300 flex flex-col overflow-hidden">
                        
                        <div class="p-8 flex-1">
                            <div class="flex justify-between items-start mb-5">
                                <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[9px] font-black uppercase tracking-widest rounded-lg border border-indigo-100 dark:border-indigo-500/20">
                                    {{ $poll->category->name ?? 'General' }}
                                </span>

                                <div class="flex items-center gap-3">
                                    {{-- VOTED BADGE --}}
                                    @if($hasVoted)
                                        <span class="flex items-center gap-1 text-[9px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 dark:bg-emerald-500/10 px-2 py-1 rounded-lg border border-emerald-100 dark:border-emerald-500/20">
                                            <i class="fas fa-check-circle"></i> Voted
                                        </span>
                                    @endif

                                    <form action="{{ route('polls.favourite', $poll) }}" method="POST">
                                        @csrf
                                        <button class="hover:scale-110 transition">
                                            @if(Auth::user()->favouritePolls->contains($poll->id))
                                                <i class="fas fa-heart text-red-500"></i>
                                            @else
                                                <i class="far fa-heart text-gray-300 dark:text-gray-600 hover:text-red-400"></i>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <h4 class="text-xl font-black text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition italic uppercase tracking-tighter leading-tight mb-3">
                                {{ $poll->title }}
                            </h4>

                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium line-clamp-2 mb-6">
                                {{ $poll->description }}
                            </p>

                            {{-- OPTIONS PREVIEW --}}
                            <div class="space-y-2 mb-6">
                                @foreach($poll->options->take(3) as $option)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-transparent dark:border-gray-700">
                                        <span class="text-[10px] font-black uppercase tracking-tight text-gray-600 dark:text-gray-400">
                                            {{ Str::limit($option->option_text, 35) }}
                                        </span>
                                        <div class="h-1.5 w-1.5 rounded-full {{ $hasVoted ? 'bg-indigo-500' : 'border border-gray-300 dark:border-gray-600' }}"></div>
                                    </div>
                                @endforeach
                                @if($poll->options->count() > 3)
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] text-center pt-1">+ {{ $poll->options->count() - 3 }} more options</p>
                                @endif
                            </div>

                            <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-gray-400">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-chart-pie text-indigo-500/50"></i>
                                    {{ number_format($poll->votes_count) }} Votes
                                </span>
                                <span>{{ $poll->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- ACTION BUTTON --}}
                        <div class="px-8 pb-8">
                            <a href="{{ route('polls.show',$poll) }}"
                               class="block w-full text-center py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all
                               {{ $hasVoted 
                                  ? 'bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-700' 
                                  : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-500/20' }}">
                                {{ $hasVoted ? 'View Results' : 'Cast Your Vote' }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            <div class="mt-12">
                {{ $allPolls->links() }}
            </div>

        </div>
    </div>

    <script>
        setTimeout(()=>{
            document.getElementById('loader').style.display='none';
            document.getElementById('content').classList.remove('hidden');
        }, 400);
    </script>
</x-app-layout>