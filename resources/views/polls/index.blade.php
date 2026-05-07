<div class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden border border-transparent dark:border-gray-700">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-900/30 text-gray-500 dark:text-gray-400 uppercase text-[10px] font-black tracking-[0.15em]">
                    <th class="px-8 py-4 text-left">Poll Information</th>
                    <th class="px-8 py-4 text-left">Author</th>
                    <th class="px-8 py-4 text-center">Current Status</th>
                    <th class="px-8 py-4 text-right">Management</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($polls as $poll)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors duration-200">
                    {{-- TITLE --}}
                    <td class="px-8 py-5">
                        <div class="flex flex-col">
                            <span class="font-black text-gray-900 dark:text-white text-sm tracking-tight uppercase">{{ $poll->title }}</span>
                            <span class="text-indigo-500 dark:text-indigo-400 text-[9px] font-black uppercase tracking-widest mt-0.5">Reference ID: #{{ $poll->id }}</span>
                        </div>
                    </td>

                    {{-- AUTHOR --}}
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-2">
                            <div class="h-6 w-6 rounded-lg bg-gray-100 dark:bg-gray-900 flex items-center justify-center border dark:border-gray-700">
                                <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400">{{ strtoupper(substr($poll->user->name, 0, 1)) }}</span>
                            </div>
                            <span class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ $poll->user->name }}</span>
                        </div>
                    </td>

                    {{-- STATUS --}}
                    <td class="px-8 py-5 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest 
                            {{ $poll->is_active 
                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' 
                                : 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $poll->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                            {{ $poll->is_active ? 'Live' : 'Closed' }}
                        </span>
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('polls.edit', $poll) }}" class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 hover:underline">
                                Edit
                            </a>
                            
                            <form action="{{ route('polls.destroy', $poll) }}" method="POST" onsubmit="return confirm('Confirm deletion?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-rose-600 hover:text-rose-700 dark:text-rose-500 transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- EMPTY STATE --}}
    @if($polls->isEmpty())
        <div class="py-12 text-center bg-white dark:bg-gray-800">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">No polls available in this directory</p>
        </div>
    @endif
</div>