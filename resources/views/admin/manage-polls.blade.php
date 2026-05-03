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
                         placeholder="Search polls by title..." 
                         class="w-full pl-10 border-gray-200 focus:ring-indigo-500 rounded-lg" />
        </div>

        <!-- Your Category Dropdown -->
        <select name="category" class="md:w-48 border-gray-200 focus:ring-indigo-500 rounded-lg text-sm">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <x-primary-button type="submit">Filter</x-primary-button>
    </form>
</div>

            <!-- Polls Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Poll Title</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Creator</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Votes</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($allPolls as $poll)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $poll->title }}</div>
                                <div class="text-xs text-gray-500">{{ $poll->category->name ?? 'Uncategorized' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $poll->user->name }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-indigo-600">
                                {{ $poll->votes_count }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <a href="{{ route('polls.show', $poll) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                
                                <!-- Delete Button (Visible to Admins) -->
                                <form action="{{ route('polls.destroy', $poll) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this poll permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $allPolls->links() }}
            </div>
        </div>
    </div>
</x-app-layout>