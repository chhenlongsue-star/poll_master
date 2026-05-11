<x-app-layout>
    {{-- ADDED: FontAwesome CDN to ensure icons render --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="py-12 bg-gray-100 dark:bg-gray-950 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- PAGE HEADER --}}
            <div class="mb-10">
                <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">My Activity</h2>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-black uppercase tracking-[0.2em]">Track your contributions and interactions across the platform.</p>
            </div>

            {{-- NAVIGATION TABS --}}
            <div class="border-b border-gray-200 dark:border-gray-800 mb-10">
                <nav class="-mb-px flex space-x-10">
                    <a href="{{ route('polls.my-content', ['tab' => 'my-polls']) }}" 
                       class="{{ $tab == 'my-polls' 
                            ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }} 
                            pb-4 px-1 border-b-4 font-black text-[11px] uppercase tracking-widest transition-all">
                        <i class="fas fa-folder-open mr-2"></i> Created Polls
                    </a>

                    <a href="{{ route('polls.my-content', ['tab' => 'drafts']) }}" 
                       class="{{ $tab == 'drafts' 
                            ? 'border-amber-500 text-amber-500' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }} 
                            pb-4 px-1 border-b-4 font-black text-[11px] uppercase tracking-widest transition-all">
                        <i class="fas fa-file-signature mr-2"></i> Drafts
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

            {{-- POLL GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($content as $poll)
                    <div class="group bg-white dark:bg-gray-900 rounded-[2.5rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-transparent dark:border-gray-800 flex flex-col hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        
                        <div class="p-8 flex-grow">
                            <div class="flex justify-between items-start mb-5">
                                <div class="flex gap-2">
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-500/20">
                                        {{ $poll->category->name ?? 'General' }}
                                    </span>

                                    @if($poll->is_banned)
                                        <div class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-500/20 inline-flex items-center cursor-not-allowed">
                                            <i class="fas fa-ban mr-1"></i> Banned
                                        </div>
                                    @else
                                        <form action="{{ route('polls.toggle-status', $poll) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            
                                            @if(!$poll->is_active)
                                                <button type="submit" title="Click to Publish" 
                                                        class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-500/20 hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-all active:scale-95">
                                                    <i class="fas fa-eye-slash mr-1"></i> Hidden
                                                </button>
                                            @else
                                                <button type="submit" title="Click to Hide" 
                                                        class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-500/20 hover:bg-green-100 dark:hover:bg-green-500/20 transition-all active:scale-95">
                                                    <i class="fas fa-eye mr-1"></i> Public
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                                
                                <form action="{{ route('polls.favourite', $poll) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 bg-gray-50 dark:bg-gray-800 rounded-xl hover:scale-110 transition">
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

                        <div class="bg-gray-50/50 dark:bg-gray-800/50 px-8 py-5 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center">
                            <a href="{{ route('polls.show', $poll) }}" class="text-[10px] font-black uppercase tracking-widest text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center">
                                Details <i class="fas fa-chevron-right ml-2 text-[8px]"></i>
                            </a>

                            {{-- UPDATED: Combined conditions to ensure Admins/Sub-Admins see actions --}}
                            @if(($tab == 'my-polls' || $tab == 'drafts') && (Auth::id() === $poll->user_id || Auth::user()->role === 'admin' || Auth::user()->role === 'sub_admin'))
                                <div class="flex items-center gap-4">
                                    
                                    {{-- Admin Ban Toggle (Gavel Icon) --}}
                                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'sub_admin')
                                        <form action="{{ route('admin.polls.toggle-ban', $poll) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" title="{{ $poll->is_banned ? 'Unban' : 'Ban' }}" class="transition hover:scale-110">
                                                <i class="fas fa-gavel text-sm {{ $poll->is_banned ? 'text-red-600' : 'text-gray-400 hover:text-red-500' }}"></i>
                                            </button>
                                        </form>
                                    @endif

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
                    <div class="col-span-full bg-white dark:bg-gray-900 rounded-[2.5rem] border-2 border-dashed border-gray-200 dark:border-gray-800 py-24 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-50 dark:bg-gray-800 rounded-2xl mb-6">
                            <i class="fas fa-ghost text-2xl text-gray-300 dark:text-gray-600"></i>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">Void Detected: No {{ $tab }} found</p>
                        @if($tab == 'my-polls' || $tab == 'drafts')
                            <a href="{{ route('polls.create') }}" class="mt-6 inline-block bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest px-8 py-3 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20">
                                Create New Poll
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