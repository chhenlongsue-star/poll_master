<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- PAGE HEADER --}}
            <div class="mb-10">
                <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">My Activity</h2>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-black uppercase tracking-[0.2em]">Track your contributions and interactions across the platform.</p>
            </div>

            <div class="border-b border-gray-200 dark:border-gray-700 mb-10">
                <nav class="-mb-px flex space-x-10">
                    <a href="{{ route('polls.my-content', ['tab' => 'my-polls']) }}" 
                       class="{{ $tab == 'my-polls' 
                            ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }} 
                            pb-4 px-1 border-b-4 font-black text-[11px] uppercase tracking-widest transition-all">
                        <i class="fas fa-folder-open mr-2"></i> Created Polls
                    </a>
                    <a href="{{ route('polls.my-content', ['tab' => 'vote-history']) }}" 
                       class="{{ $tab == 'vote-history' 
                            ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }} 
                            pb-4 px-1 border-b-4 font-black text-[11px] uppercase tracking-widest transition-all">
                        <i class="fas fa-history mr-2"></i> Vote History
                    </a>
                </nav>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($content as $poll)
                    <div class="group bg-white dark:bg-gray-800 rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-transparent dark:border-gray-700 flex flex-col hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        
                        <div class="p-8 flex-grow">
                            <div class="flex justify-between items-start mb-5">
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-500/20">
                                    {{ $poll->category->name ?? 'General' }}
                                </span>
                                
                                <form action="{{ route('polls.favourite', $poll) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 bg-gray-50 dark:bg-gray-900 rounded-xl hover:scale-110 transition">
                                        <i class="{{ Auth::user()->favouritePolls->contains($poll->id) ? 'fas' : 'far' }} fa-heart text-red-500"></i>
                                    </button>
                                </form>
                            </div>

                            <h4 class="text-xl font-black text-gray-900 dark:text-white leading-tight mb-3 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition italic uppercase tracking-tighter">
                                {{ $poll->title }}
                            </h4>
                            <p class="text-gray-500 dark:text-gray-400 text-xs font-medium line-clamp-2 leading-relaxed">
                                {{ Str::limit($poll->description, 80) }}
                            </p>
                            
                            <div class="mt-6 flex items-center gap-2">
                                <div class="h-1.5 w-1.5 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.6)]"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">{{ number_format($poll->votes_count) }} Votes Cast</span>
                            </div>
                        </div>

                        <div class="bg-gray-50/50 dark:bg-gray-900/50 px-8 py-5 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                            <a href="{{ route('polls.show', $poll) }}" class="text-[10px] font-black uppercase tracking-widest text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center">
                                Details <i class="fas fa-chevron-right ml-2 text-[8px]"></i>
                            </a>

                            @if($tab == 'my-polls' && (Auth::id() === $poll->user_id || in_array(Auth::user()->role, ['admin', 'sub_admin'])))
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('polls.edit', $poll) }}" class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition" title="Edit Poll">
                                        <i class="fas fa-pen-nib text-sm"></i>
                                    </a>

                                    <form action="{{ route('polls.destroy', $poll) }}" method="POST" onsubmit="return confirm('Permanently delete this poll?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Delete Poll">
                                            <i class="fas fa-trash-alt text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-800 rounded-3xl border-2 border-dashed border-gray-200 dark:border-gray-700 py-24 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-2xl mb-6">
                            <i class="fas fa-ghost text-2xl text-gray-300 dark:text-gray-600"></i>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">Void Detected: No data found here</p>
                        @if($tab == 'my-polls')
                            <a href="{{ route('polls.create') }}" class="mt-6 inline-block bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest px-8 py-3 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20">
                                Create Your First Poll
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $content->links() }}
            </div>
        </div>
    </div>
</x-app-layout>