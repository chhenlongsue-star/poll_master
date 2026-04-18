<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Poll Master Dashboard') }}
            </h2>
            <a href="{{ route('polls.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
                + Create New Poll
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <x-text-input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search for polls by title or description..." 
                               class="w-full" />
                    </div>
                    <div class="w-full md:w-48">
                        <select name="category" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <x-primary-button type="submit" class="justify-center">
                        {{ __('Search') }}
                    </x-primary-button>
                </form>
            </div>

            @if($adminPolls->count() > 0)
            <section>
                <h3 class="text-lg font-bold text-indigo-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" /><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" /></svg>
                    Official Admin Polls
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($adminPolls as $poll)
                        <div class="bg-indigo-50 border-l-4 border-indigo-600 p-6 rounded-lg shadow-sm hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">{{ $poll->category->name ?? 'Uncategorized' }}</span>
                                
                                @if(Auth::id() === $poll->user_id || Auth::user()->role === 'admin')
                                <div class="flex space-x-2">
                                    <a href="{{ route('polls.edit', $poll) }}" class="text-gray-400 hover:text-indigo-600 transition"><i class="fas fa-edit text-xs">Edit</i></a>
                                    <form action="{{ route('polls.destroy', $poll) }}" method="POST" onsubmit="return confirm('Delete this poll?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition text-xs">Del</button>
                                    </form>
                                </div>
                                @endif
                            </div>
                            
                            <h4 class="text-xl font-bold mt-1 text-gray-900">{{ $poll->title }}</h4>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $poll->description }}</p>
                            <div class="mt-4 flex justify-between items-center">
                                <span class="text-xs text-gray-500">{{ $poll->votes_count }} votes</span>
                                <a href="{{ route('polls.show', $poll) }}" class="text-indigo-600 font-bold hover:underline italic">Vote Now →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <section>
                    <h3 class="text-lg font-bold text-gray-800 mb-4 italic">🔥 Most Popular</h3>
                    <div class="space-y-4">
                        @forelse($popularPolls as $poll)
                            <div class="bg-white p-4 rounded-lg shadow-sm flex items-center justify-between border border-gray-100 hover:border-indigo-200 transition">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $poll->title }}</h4>
                                    <p class="text-xs text-gray-500">{{ $poll->votes_count }} people have voted</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    @if(Auth::id() === $poll->user_id || Auth::user()->role === 'admin')
                                        <a href="{{ route('polls.edit', $poll) }}" class="text-xs text-gray-400 hover:text-indigo-600">Edit</a>
                                    @endif
                                    <a href="{{ route('polls.show', $poll) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-indigo-600 hover:text-white transition text-sm font-medium">Open</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm italic">No popular polls found.</p>
                        @endforelse
                    </div>
                </section>

                <section>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">🕒 Recently Added</h3>
                    <div class="space-y-4">
                        @forelse($recentPolls as $poll)
                            <div class="bg-white p-4 rounded-lg shadow-sm flex items-center justify-between border border-gray-100 hover:border-indigo-200 transition">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $poll->title }}</h4>
                                    <p class="text-xs text-gray-400">By {{ $poll->user->name ?? 'User' }} • {{ $poll->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    @if(Auth::id() === $poll->user_id || Auth::user()->role === 'admin')
                                        <form action="{{ route('polls.destroy', $poll) }}" method="POST" onsubmit="return confirm('Delete?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-gray-400 hover:text-red-600">Delete</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('polls.show', $poll) }}" class="text-indigo-600 text-sm hover:underline font-medium">View Poll</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm italic">No recent polls found.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <section>
                <h3 class="text-lg font-bold text-gray-800 mb-4">👥 User Community Polls</h3>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poll Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Votes</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($userPolls as $poll)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $poll->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-xs">
                                        {{ $poll->category->name ?? 'None' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $poll->votes_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                    @if(Auth::id() === $poll->user_id || Auth::user()->role === 'admin')
                                        <a href="{{ route('polls.edit', $poll) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route('polls.destroy', $poll) }}" method="POST" class="inline" onsubmit="return confirm('Delete this poll?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('polls.show', $poll) }}" class="text-indigo-600 hover:text-indigo-900 font-bold ml-4">Vote</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">No community polls available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </div>
</x-app-layout>