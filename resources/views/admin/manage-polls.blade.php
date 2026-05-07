<x-app-layout>
    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- PAGE TITLE --}}
            <div class="mb-8">
                <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Manage All Polls</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Moderate system-wide voting content and visibility.</p>
            </div>

            {{-- SEARCH & FILTERS --}}
            <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm border border-transparent dark:border-gray-700 mb-8">
                <form action="{{ route('admin.polls.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Search Database</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search by title or creator..." 
                                   class="w-full pl-4 pr-4 py-2 bg-gray-50 dark:bg-gray-900 border-none rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-indigo-500 transition shadow-inner">
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Category</label>
                        <select name="category" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-xl text-sm dark:text-white focus:ring-2 focus:ring-indigo-500 shadow-inner">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-2.5 rounded-xl uppercase text-[10px] tracking-widest transition shadow-lg shadow-indigo-500/20">
                            Apply Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- POLLS TABLE --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-transparent dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-gray-900/30 text-gray-500 dark:text-gray-400 uppercase text-[10px] font-black tracking-widest">
                                <th class="px-8 py-4 text-left">Poll Details</th>
                                <th class="px-8 py-4 text-left">Author</th>
                                <th class="px-8 py-4 text-center">Visibility</th>
                                <th class="px-8 py-4 text-center">Engagement</th>
                                <th class="px-8 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($allPolls as $poll)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-black text-gray-900 dark:text-white text-sm">{{ $poll->title }}</span>
                                        <span class="text-indigo-600 dark:text-indigo-400 text-[10px] uppercase font-black tracking-wider">{{ $poll->category->name }}</span>
                                    </div>
                                </td>
                                
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                                            <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400">
                                                {{ strtoupper(substr($poll->user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <span class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ $poll->user->name }}</span>
                                    </div>
                                </td>

                                <td class="px-8 py-5 text-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest 
                                        {{ $poll->is_active 
                                            ? 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400' 
                                            : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                        {{ $poll->is_active ? 'Public' : 'Hidden' }}
                                    </span>
                                </td>

                                <td class="px-8 py-5 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm font-black text-gray-900 dark:text-white">{{ number_format($poll->votes_count) }}</span>
                                        <span class="text-[9px] uppercase text-gray-400 font-bold tracking-widest">Votes</span>
                                    </div>
                                </td>

                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end gap-3">
                                        <form action="{{ route('admin.polls.toggle-active', $poll) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" 
                                                    class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border transition
                                                    {{ $poll->is_active 
                                                        ? 'text-orange-500 border-orange-500 hover:bg-orange-500 hover:text-white' 
                                                        : 'text-indigo-500 border-indigo-500 hover:bg-indigo-500 hover:text-white' }}">
                                                {{ $poll->is_active ? 'Hide Poll' : 'Publish' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- NO DATA STATE --}}
                @if($allPolls->isEmpty())
                    <div class="text-center py-20 bg-white dark:bg-gray-800">
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium italic">No polls match your current filters.</p>
                    </div>
                @endif

                {{-- PAGINATION --}}
                <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-900/30 border-t dark:border-gray-700">
                    {{ $allPolls->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>