<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800">My Activity</h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('polls.my-content', ['tab' => 'my-polls']) }}" 
                       class="{{ $tab == 'my-polls' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500' }} py-4 px-1 border-b-2 font-bold text-sm">
                        📁 My Created Polls
                    </a>
                    <a href="{{ route('polls.my-content', ['tab' => 'vote-history']) }}" 
                       class="{{ $tab == 'vote-history' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500' }} py-4 px-1 border-b-2 font-bold text-sm">
                        ✅ My Vote History
                    </a>
                </nav>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($content as $poll)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col hover:shadow-md transition overflow-hidden">
                        
                        <!-- Top Section -->
                        <div class="p-6 flex-grow">
                            <div class="flex justify-between mb-3">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $poll->category->name ?? 'General' }}
                                </span>
                                
                                <form action="{{ route('polls.favourite', $poll) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="hover:scale-110 transition transform">
                                        <i class="{{ Auth::user()->favouritePolls->contains($poll->id) ? 'fas' : 'far' }} fa-heart text-red-500"></i>
                                    </button>
                                </form>
                            </div>

                            <h4 class="text-lg font-extrabold text-gray-900 leading-tight mb-2">{{ $poll->title }}</h4>
                            <p class="text-gray-500 text-sm line-clamp-2">{{ Str::limit($poll->description, 80) }}</p>
                            
                            <div class="mt-4 flex items-center text-indigo-600">
                                <span class="text-xs font-black uppercase tracking-tighter">{{ $poll->votes_count }} Votes Cast</span>
                            </div>
                        </div>

                        <!-- Management Footer (Only visible on My Polls tab for owners/admins) -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                            <a href="{{ route('polls.show', $poll) }}" class="text-sm font-bold text-gray-900 hover:text-indigo-600 flex items-center">
                                View Details <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </a>

                            {{-- SHIELD: Show Edit/Delete if User is Creator OR Admin/Sub-Admin --}}
                            @if($tab == 'my-polls' && (Auth::id() === $poll->user_id || in_array(Auth::user()->role, ['admin', 'sub_admin'])))
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('polls.edit', $poll) }}" class="text-gray-400 hover:text-blue-600 transition" title="Edit Poll">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('polls.destroy', $poll) }}" method="POST" onsubmit="return confirm('Permanently delete this poll?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Delete Poll">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl border-2 border-dashed border-gray-200 py-20 text-center">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 font-medium">Nothing found here yet.</p>
                        @if($tab == 'my-polls')
                            <a href="{{ route('polls.create') }}" class="mt-4 inline-block text-indigo-600 font-bold hover:underline">Create your first poll →</a>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $content->links() }}
            </div>
        </div>
    </div>
</x-app-layout>