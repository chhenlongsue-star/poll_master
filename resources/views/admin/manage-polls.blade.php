<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800">🛡️ Manage All Polls</h2>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Search & Filter Bar -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                <form action="{{ route('admin.polls.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    
                    <!-- Search Input -->
                    <div class="flex-1 relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <x-text-input type="text" name="search" value="{{ request('search') }}" 
                                     placeholder="Search polls by title or creator..." 
                                     class="w-full pl-10 border-gray-200 focus:ring-indigo-500 rounded-lg" />
                    </div>

                    <!-- Category Dropdown -->
                    <select name="category" class="md:w-48 border-gray-200 focus:ring-indigo-500 rounded-lg text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <x-primary-button type="submit">Filter</x-primary-button>
                    
                    @if(request()->has('search') || request()->has('category'))
                        <a href="{{ route('admin.polls.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Polls Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Poll Details</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Creator</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Votes</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($allPolls as $poll)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $poll->title }}</div>
                                <div class="text-[10px] uppercase font-black text-indigo-500">{{ $poll->category->name ?? 'Uncategorized' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600 font-medium">{{ $poll->user->name }}</div>
                                    @if($poll->user->role !== 'user')
                                        <span class="ml-2 px-1.5 py-0.5 text-[9px] bg-amber-100 text-amber-700 rounded uppercase font-bold">Staff</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($poll->is_active)
                                    <span class="px-2 py-1 text-[10px] font-bold bg-green-100 text-green-700 rounded-full uppercase tracking-tighter">
                                        <i class="fas fa-check-circle mr-1"></i> Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-[10px] font-bold bg-gray-100 text-gray-500 rounded-full uppercase tracking-tighter">
                                        <i class="fas fa-eye-slash mr-1"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-black text-gray-900">
                                {{ number_format($poll->votes_count) }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <div class="flex justify-end items-center space-x-3">
                                    <a href="{{ route('polls.show', $poll) }}" class="text-gray-400 hover:text-indigo-600 transition" title="View Poll">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>

                                    <a href="{{ route('polls.edit', $poll) }}" class="text-gray-400 hover:text-blue-600 transition" title="Edit Poll">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Status Toggle Button -->
                                    <form action="{{ route('admin.polls.toggle-active', $poll) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $poll->is_active ? 'text-amber-500 hover:text-amber-700' : 'text-green-500 hover:text-green-700' }} transition" 
                                                title="{{ $poll->is_active ? 'Deactivate Poll' : 'Activate Poll' }}">
                                            <i class="fas {{ $poll->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} fa-lg"></i>
                                        </button>
                                    </form>

                                    <!-- Delete Button -->
                                    <form action="{{ route('polls.destroy', $poll) }}" method="POST" class="inline" onsubmit="return confirm('Delete this poll permanently?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Delete Poll">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $allPolls->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>