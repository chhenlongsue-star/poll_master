<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Poll Details') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="mb-8">
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-bold rounded-full uppercase">
                        {{ $poll->category->name }}
                    </span>
                    <h1 class="text-3xl font-bold text-gray-900 mt-4">{{ $poll->title }}</h1>
                    <p class="text-gray-600 mt-2">{{ $poll->description }}</p>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <span class="font-medium text-gray-700">Asked by {{ $poll->user->name }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $poll->created_at->diffForHumans() }}</span>
                        <span class="mx-2">•</span>
                        <span class="font-bold text-indigo-600">{{ $poll->votes_count }} Total Votes</span>
                    </div>
                </div>

                <hr class="my-8">

                @if($userVote)
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Current Results
                        </h3>
                        
                        @foreach($poll->options as $option)
                            @php
                                $percentage = $poll->votes_count > 0 ? round(($option->votes->count() / $poll->votes_count) * 100) : 0;
                                $isUserChoice = $userVote->option_id === $option->id;
                            @endphp
                            
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $option->option_text }} 
                                        @if($isUserChoice) 
                                            <span class="text-xs font-bold text-indigo-600 ml-2">(Your Choice)</span> 
                                        @endif
                                    </span>
                                    <span class="text-sm font-bold text-gray-900">{{ $percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    <div class="bg-indigo-600 h-4 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $option->votes->count() }} votes</p>
                            </div>
                        @endforeach
                        
                        <div class="mt-8 p-4 bg-gray-50 rounded-md border border-gray-200 text-center text-sm text-gray-600 italic">
                            You submitted your vote {{ $userVote->created_at->diffForHumans() }}.
                        </div>
                    </div>

                @else
                    <form action="{{ route('polls.vote', $poll) }}" method="POST">
                        @csrf
                        <h3 class="text-lg font-bold text-gray-800 mb-6 italic">Select one option:</h3>
                        
                        <div class="space-y-4">
                            @foreach($poll->options as $option)
                                <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition">
                                    <input type="radio" name="option_id" value="{{ $option->id }}" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" required>
                                    <span class="ml-4 font-medium text-gray-900">{{ $option->option_text }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-10">
                            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-md font-bold text-lg hover:bg-indigo-700 transition shadow-lg">
                                Cast My Vote
                            </button>
                            <p class="text-center text-xs text-gray-400 mt-4">Note: You can only vote once per poll.</p>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>