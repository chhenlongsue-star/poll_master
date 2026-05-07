<x-app-layout>
    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- PAGE HEADER --}}
            <div class="mb-8">
                <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Edit Poll</h2>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-black uppercase tracking-widest">Update the details for: <span class="text-indigo-600 dark:text-indigo-400 italic">"{{ $poll->title }}"</span></p>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl p-10 border border-transparent dark:border-gray-700">
                
                <form action="{{ route('polls.update', $poll) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- POLL TITLE --}}
                    <div class="mb-8">
                        <label for="title" class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 mb-2 block">Poll Question</label>
                        <input id="title" type="text" name="title" value="{{ old('title', $poll->title) }}" required 
                               class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl py-4 px-6 text-lg font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition shadow-inner">
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        {{-- CATEGORY --}}
                        <div>
                            <label for="category_id" class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Category</label>
                            <select name="category_id" id="category_id" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-xl py-3 px-4 text-sm font-bold text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500 shadow-inner">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $poll->category_id == $category->id ? 'selected' : '' }}>
                                        {{ strtoupper($category->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ENGAGEMENT STATUS --}}
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Current Engagement</label>
                            <div class="flex items-center justify-between py-3 px-5 bg-gray-50 dark:bg-gray-900/80 rounded-xl border border-gray-100 dark:border-gray-700">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Votes</span>
                                <span class="text-sm font-black text-indigo-600 dark:text-indigo-400">{{ number_format($poll->votes_count ?? 0) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="mb-10">
                        <label for="description" class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Context / Description</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl py-4 px-6 text-sm text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 shadow-inner">{{ old('description', $poll->description) }}</textarea>
                    </div>

                    {{-- NOTICE --}}
                    <div class="mb-10 p-5 bg-amber-50 dark:bg-amber-900/10 rounded-2xl border border-amber-100 dark:border-amber-900/30">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-[10px] font-bold text-amber-700 dark:text-amber-500 uppercase tracking-tight leading-relaxed">
                                Note: You are editing the metadata. To change voting options, please moderate the individual options via the administrative panel if enabled.
                            </p>
                        </div>
                    </div>

                    <div class="h-px w-full bg-gray-100 dark:bg-gray-700 mb-10"></div>

                    {{-- ACTIONS --}}
                    <div class="flex flex-col md:flex-row items-center gap-4">
                        <button type="submit" class="w-full md:flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl uppercase text-xs tracking-widest transition-all shadow-xl shadow-indigo-500/20 transform hover:-translate-y-1">
                            Save Changes
                        </button>
                        
                        <a href="{{ route('dashboard') }}" class="w-full md:w-auto px-10 py-4 bg-gray-100 dark:bg-gray-900 text-gray-500 dark:text-gray-400 font-black rounded-2xl uppercase text-[10px] tracking-widest text-center hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>