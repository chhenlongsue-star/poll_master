<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Poll Master | Create & Vote</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50">
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-indigo-600 italic">Poll Master</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 font-semibold hover:text-indigo-600 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm text-gray-700 font-semibold hover:text-indigo-600 transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <div class="relative pt-16 pb-20 overflow-hidden bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                        <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block">The simplest way to</span>
                                <span class="block text-indigo-600">collect opinions.</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-xl lg:text-lg xl:text-xl">
                                Create interactive polls, share them with your community, and visualize results in real-time. Whether it's for school projects or quick decisions, Poll Master has you covered.
                            </p>
                            <div class="mt-8 sm:max-w-lg sm:mx-auto sm:text-center lg:text-left">
                                <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition">
                                    Get Started for Free
                                </a>
                            </div>
                        </div>
                        <div class="mt-12 relative sm:max-w-lg sm:mx-auto lg:mt-0 lg:max-w-none lg:mx-0 lg:col-span-6 lg:flex lg:items-center">
                            <div class="bg-indigo-100 rounded-2xl p-8 shadow-inner w-full">
                                <div class="space-y-4">
                                    <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-indigo-500">
                                        <div class="h-2 w-24 bg-gray-200 rounded mb-2"></div>
                                        <div class="h-4 w-48 bg-gray-300 rounded"></div>
                                    </div>
                                    <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 opacity-75">
                                        <div class="h-2 w-24 bg-gray-200 rounded mb-2"></div>
                                        <div class="h-4 w-32 bg-gray-300 rounded"></div>
                                    </div>
                                    <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500 opacity-50">
                                        <div class="h-2 w-24 bg-gray-200 rounded mb-2"></div>
                                        <div class="h-4 w-40 bg-gray-300 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 py-16 border-t border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-extrabold text-gray-900 flex items-center justify-center">
                            Trending Polls <span class="ml-2">🔥</span>
                        </h2>
                        <p class="mt-2 text-gray-600">See what the community is deciding right now.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @forelse($trendingPolls as $poll)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition flex flex-col">
                                <div class="p-6 flex-1">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase rounded border border-indigo-100">
                                            {{ $poll->category->name ?? 'General' }}
                                        </span>
                                        <span class="text-gray-400 text-xs font-medium">{{ $poll->votes_count }} votes</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight">{{ $poll->title }}</h3>
                                    <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $poll->description }}</p>
                                    
                                    <div class="space-y-2">
                                        @foreach($poll->options->take(2) as $option)
                                            <div class="w-full bg-gray-50 border border-gray-100 rounded p-2 text-xs text-gray-600 flex justify-between">
                                                <span>{{ Str::limit($option->option_text, 30) }}</span>
                                                <span class="text-gray-300">○</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-50 border-t border-gray-100 mt-auto">
                                    <a href="{{ route('login') }}" class="block text-center w-full py-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                                        Join the Vote →
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-10">
                                <p class="text-gray-400 italic">No trending polls available yet. Be the first to create one!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900">Why use Poll Master?</h2>
                    <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-3">
                        <div class="p-6 border border-gray-100 rounded-xl hover:shadow-md transition">
                            <div class="text-indigo-600 text-3xl mb-4">⚡</div>
                            <h3 class="text-lg font-bold text-gray-900">Fast Creation</h3>
                            <p class="text-gray-500 text-sm mt-2">Set up your question and options in seconds.</p>
                        </div>
                        <div class="p-6 border border-gray-100 rounded-xl hover:shadow-md transition">
                            <div class="text-indigo-600 text-3xl mb-4">📊</div>
                            <h3 class="text-lg font-bold text-gray-900">Live Results</h3>
                            <p class="text-gray-500 text-sm mt-2">Watch the votes roll in with real-time counters.</p>
                        </div>
                        <div class="p-6 border border-gray-100 rounded-xl hover:shadow-md transition">
                            <div class="text-indigo-600 text-3xl mb-4">🔒</div>
                            <h3 class="text-lg font-bold text-gray-900">Secure Voting</h3>
                            <p class="text-gray-500 text-sm mt-2">One user, one vote. No spam, just real data.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-gray-50 border-t border-gray-200 py-12">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p class="text-gray-400 text-sm">&copy; 2026 Poll Master Project. Built with Laravel 12.</p>
            </div>
        </footer>
    </body>
</html>