<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
                {{ __('Poll Master') }}
            </h2>
            <a href="{{ route('polls.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition shadow-md flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Poll
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- 1. SEARCH & FILTERS -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <x-text-input type="text" name="search" value="{{ request('search') }}" 
                                     placeholder="Search for polls..." 
                                     class="w-full pl-10 border-gray-200 focus:ring-indigo-500 rounded-lg" />
                    </div>
                    <select name="category" class="md:w-48 border-gray-200 focus:ring-indigo-500 rounded-lg text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-primary-button type="submit" class="rounded-lg px-6">Search</x-primary-button>
                </form>
            </div>

            <!-- 2. NAVIGATION TABS (Essential for UX) -->
            <div class="border-b border-gray-200">
    <nav class="-mb-px flex space-x-8 overflow-x-auto">
        @php $currentTab = request('tab', 'all'); @endphp

        <!-- All Polls -->
        <a href="{{ route('dashboard', ['tab' => 'all']) }}" 
           class="{{ $currentTab == 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }} whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm transition">
            🌐 All Polls
        </a>

        <!-- Official (Admin) -->
        <a href="{{ route('dashboard', ['tab' => 'official']) }}" 
           class="{{ $currentTab == 'official' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
            🛡️ Official
        </a>

        <!-- Trending -->
        <a href="{{ route('dashboard', ['tab' => 'trending']) }}" 
           class="{{ $currentTab == 'trending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
            🔥 Trending
        </a>

        <!-- User Community (New Button) -->
        <a href="{{ route('dashboard', ['tab' => 'community']) }}" 
           class="{{ $currentTab == 'community' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
            👥 Community
        </a>

        <!-- Favorites -->
        <a href="{{ route('dashboard', ['tab' => 'favorites']) }}" 
           class="{{ $currentTab == 'favorites' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
            ⭐ Favorites
        </a>
    </nav>
</div>

            <!-- 3. POLL GRID (Unified Design) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($allPolls as $poll)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                    <!-- Top Ribbon for Admin Polls -->
                    @if($poll->user->role === 'admin' || $poll->user->role === 'sub_admin')
                        <div class="bg-indigo-600 text-white text-[10px] uppercase font-black px-3 py-1 tracking-widest">Official Admin Poll</div>
                    @endif

                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                {{ $poll->category->name ?? 'General' }}
                            </span>
                            
                            <!-- Favorite Button Toggle -->
                            <form action="{{ route('polls.favourite', $poll) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-gray-300 hover:text-red-500 transition">
                                    <i class="fa{{ Auth::user()->favouritePolls->contains($poll->id) ? 's' : 'r' }} fa-heart text-lg"></i>
                                </button>
                            </form>
                        </div>

                        <h4 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $poll->title }}</h4>
                        <p class="text-sm text-gray-500 mt-2 line-clamp-2 h-10">{{ $poll->description }}</p>

                        <div class="mt-6 flex items-center justify-between border-t border-gray-50 pt-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                    {{ substr($poll->user->name, 0, 1) }}
                                </div>
                                <div class="ml-2">
                                    <p class="text-xs font-bold text-gray-800">{{ $poll->user->name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $poll->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-indigo-600">{{ $poll->votes_count }} Votes</p>
                            </div>
                        </div>

                        <a href="{{ route('polls.show', $poll) }}" class="mt-4 block w-full text-center bg-gray-900 text-white py-2 rounded-lg font-bold text-sm hover:bg-indigo-600 transition">
                            Open Poll
                        </a>
                    </div>
                </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <p class="text-gray-400 italic">No polls match your criteria.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $allPolls->links() }}
            </div>
        </div>
    </div>
</x-app-layout>