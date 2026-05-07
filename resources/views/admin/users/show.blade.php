<x-app-layout>
    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- NAVIGATION --}}
            <div class="mb-6">
                <a href="{{ route('admin.dashboard') }}" class="text-[10px] font-black text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition uppercase tracking-widest flex items-center gap-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to System Overview
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl p-8 border border-transparent dark:border-gray-700">
                
                <div class="flex flex-col md:flex-row items-center justify-between border-b dark:border-gray-700 pb-10 mb-10">
                    <div class="flex flex-col md:flex-row items-center text-center md:text-left">
                        <div class="relative">
                            <img src="{{ $user->avatar }}" 
                                 class="w-28 h-28 rounded-2xl object-cover border-4 border-white dark:border-gray-700 shadow-xl"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=6366F1&background=EEF2FF&bold=true'">
                            @if($user->last_seen_at >= now()->subMinutes(5))
                                <span class="absolute -top-2 -right-2 block h-5 w-5 rounded-full bg-green-500 border-4 border-white dark:border-gray-800"></span>
                            @endif
                        </div>
                        <div class="md:ml-8 mt-4 md:mt-0">
                            <h2 class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ $user->name }}</h2>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            
                            <div class="flex items-center justify-center md:justify-start mt-3 gap-3">
                                <span class="px-4 py-1 bg-indigo-600 text-white text-[10px] font-black rounded-lg uppercase tracking-widest shadow-lg shadow-indigo-500/20">
                                    {{ $user->role }}
                                </span>
                                @if($user->is_banned)
                                    <span class="px-4 py-1 bg-red-500 text-white text-[10px] font-black rounded-lg uppercase tracking-widest shadow-lg shadow-red-500/20">
                                        RESTRICTED
                                    </span>
                                @else
                                    <span class="px-4 py-1 bg-emerald-500/10 text-emerald-500 text-[10px] font-black rounded-lg uppercase tracking-widest border border-emerald-500/20">
                                        Active Account
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-3 mt-8 md:mt-0 w-full md:w-auto">
                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="w-full px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition shadow-lg
                                {{ $user->is_banned 
                                    ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-emerald-500/20' 
                                    : 'bg-orange-500 hover:bg-orange-600 text-white shadow-orange-500/20' }}">
                                {{ $user->is_banned ? 'Restore Access' : 'Restrict Account' }}
                            </button>
                        </form>
                        
                        @if(Auth::user()->role === 'admin')
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('PERMANENTLY DELETE USER? This cannot be undone.');">
                            @csrf @method('DELETE')
                            <button class="w-full px-6 py-2.5 bg-transparent border-2 border-red-600 text-red-600 hover:bg-red-600 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition">
                                Delete Permanently
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-10">
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-2xl border dark:border-gray-700">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Lifetime Polls</p>
                        <h4 class="text-2xl font-black text-gray-900 dark:text-white">{{ $polls->total() }}</h4>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-2xl border dark:border-gray-700">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Last Activity</p>
                        <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase">
                            {{ $user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'Never' }}
                        </h4>
                    </div>
                </div>

                <div class="flex items-center gap-4 mb-8">
                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Authored Content</h3>
                    <div class="h-px flex-1 bg-gray-100 dark:bg-gray-700"></div>
                </div>

                <div class="space-y-4">
                    @forelse($polls as $poll)
                        <div class="p-6 border border-gray-100 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800/50 flex justify-between items-center group hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <h4 class="font-black text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ $poll->title }}</h4>
                                    <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[8px] font-black uppercase rounded">
                                        {{ $poll->category->name }}
                                    </span>
                                </div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Published {{ $poll->created_at->format('M d, Y') }}</p>
                            </div>
                            
                            <div class="flex items-center gap-6">
                                <div class="text-center">
                                    <span class="block text-xl font-black text-indigo-600 dark:text-indigo-400 leading-none">{{ number_format($poll->votes_count) }}</span>
                                    <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Votes</span>
                                </div>
                                <div class="h-8 w-px bg-gray-100 dark:bg-gray-700"></div>
                                <a href="{{ route('admin.polls.index', ['search' => $poll->title]) }}" class="p-2 text-gray-400 hover:text-indigo-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 bg-gray-50 dark:bg-gray-900/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                            <p class="text-gray-500 dark:text-gray-400 text-xs font-black uppercase tracking-widest italic">No authored polls found</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-8">
                    {{ $polls->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>