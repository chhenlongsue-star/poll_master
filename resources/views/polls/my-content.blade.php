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

            <!-- Content Grid (Same as Dashboard) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($content as $poll)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                        <div class="flex justify-between mb-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                {{ $poll->category->name ?? 'General' }}
                            </span>
                            <form action="{{ route('polls.favourite', $poll) }}" method="POST">
                                @csrf
                                <button type="submit">
                                    <i class="{{ Auth::user()->favouritePolls->contains($poll->id) ? 'fas' : 'far' }} fa-heart text-red-500"></i>
                                </button>
                            </form>
                        </div>
                        <h4 class="text-lg font-bold">{{ $poll->title }}</h4>
                        <div class="mt-4 flex justify-between items-center border-t pt-4">
                            <span class="text-xs font-black text-indigo-600">{{ $poll->votes_count }} Votes</span>
                            <a href="{{ route('polls.show', $poll) }}" class="text-sm font-bold text-gray-900 hover:text-indigo-600">Open Poll →</a>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center py-10 text-gray-500 italic">Nothing found here yet.</p>
                @endforelse
            </div>

            <div class="mt-6">{{ $content->links() }}</div>
        </div>
    </div>
</x-app-layout>