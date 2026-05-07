<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tighter mb-6">Manage All Polls</h2>

            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <form action="{{ route('admin.polls.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-1 block">Search Polls</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by title or creator..." 
                               class="w-full rounded-md border-gray-200 text-sm focus:ring-indigo-500 shadow-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 mb-1 block">Category</label>
                        <select name="category" class="w-full rounded-md border-gray-200 text-sm focus:ring-indigo-500 shadow-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-2 rounded-md uppercase text-xs tracking-widest transition">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 uppercase text-[10px] font-black tracking-widest">
                                <th class="px-6 py-4 text-left">Poll Details</th>
                                <th class="px-6 py-4 text-left">Creator</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 text-left">Votes</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($allPolls as $poll)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-black text-gray-800 text-sm">{{ $poll->title }}</span>
                                        <span class="text-indigo-500 text-[10px] uppercase font-bold">{{ $poll->category->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-gray-500">{{ $poll->user->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $poll->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $poll->is_active ? 'Active' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-black">{{ $poll->votes_count }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <form action="{{ route('admin.polls.toggle-active', $poll) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-xs font-black uppercase text-indigo-600 hover:underline">
                                                {{ $poll->is_active ? 'Hide' : 'Show' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t">
                    {{ $allPolls->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>