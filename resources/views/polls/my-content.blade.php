<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Content') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Polls I Created -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-indigo-600">Polls I Created</h3>
                    <a href="{{ route('polls.create') }}" class="text-sm bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700 transition">
                        + Create New
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-600 text-sm uppercase font-bold">
                            <tr>
                                <th class="p-3">Title</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($myPolls as $poll)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 font-medium">{{ $poll->title }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 text-[10px] font-bold uppercase rounded {{ $poll->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $poll->is_active ? 'Active' : 'Closed' }}
                                    </span>
                                </td>
                                <td class="p-3 text-right space-x-3 text-sm">
                                    <a href="{{ route('polls.show', $poll) }}" class="text-indigo-600 hover:underline">View</a>
                                    <a href="{{ route('polls.edit', $poll) }}" class="text-gray-600 hover:underline">Edit</a>
                                    <form action="{{ route('polls.destroy', $poll) }}" method="POST" class="inline" onsubmit="return confirm('Delete this poll permanently?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-8 text-center text-gray-500 italic">
                                    You haven't created any polls yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- My Vote History -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-bold mb-4 text-green-600">My Vote History</h3>
                <div class="space-y-4">
                    @forelse($myVotes as $vote)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex flex-col">
                            <span class="text-gray-800">You voted on: <strong class="text-indigo-600">{{ $vote->poll->title }}</strong></span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $vote->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-xs text-gray-500 italic">{{ $vote->created_at->diffForHumans() }}</span>
                            <a href="{{ route('polls.show', $vote->poll) }}" class="text-xs font-bold text-indigo-600 hover:underline">View Poll</a>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500 italic bg-gray-50 rounded-lg">
                        You haven't cast any votes yet.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>