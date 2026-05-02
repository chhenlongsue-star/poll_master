<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('My Content') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-bold mb-4 text-indigo-600">Polls I Created</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3">Title</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myPolls as $poll)
                            <tr class="border-b">
                                <td class="p-3">{{ $poll->title }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 text-xs rounded {{ $poll->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $poll->is_active ? 'Active' : 'Closed' }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('polls.show', $poll) }}" class="text-indigo-600">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-bold mb-4 text-green-600">My Vote History</h3>
                <ul class="divide-y">
                    @foreach($myVotes as $vote)
                    <li class="py-3 flex justify-between">
                        <span>You voted on: <strong>{{ $vote->poll->title }}</strong></span>
                        <span class="text-gray-500 text-sm">{{ $vote->created_at->diffForHumans() }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>