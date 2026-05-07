<x-app-layout>
    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- PAGE HEADER --}}
            <div class="mb-8">
                <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Create New Poll</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest">Setup your question and voting options below.</p>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl p-10 border border-transparent dark:border-gray-700">
                
                <form action="{{ route('polls.store') }}" method="POST">
                    @csrf

                    {{-- POLL TITLE --}}
                    <div class="mb-8">
                        <label for="title" class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 mb-2 block">Poll Question / Title</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" required autofocus 
                               placeholder="e.g., WHAT IS THE BEST TECH STACK IN 2026?" 
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
                                    <option value="{{ $category->id }}">{{ strtoupper($category->name) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        {{-- STATUS PREVIEW --}}
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Visibility</label>
                            <div class="flex items-center gap-2 py-3 px-4 bg-indigo-50/50 dark:bg-indigo-500/5 rounded-xl border border-indigo-100 dark:border-indigo-500/20">
                                <div class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></div>
                                <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-tighter">Public after launch</span>
                            </div>
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="mb-10">
                        <label for="description" class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Context / Description (Optional)</label>
                        <textarea name="description" id="description" rows="3" 
                                  placeholder="Provide more details about this poll..."
                                  class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl py-4 px-6 text-sm text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500 shadow-inner"></textarea>
                    </div>

                    <div class="h-px w-full bg-gray-100 dark:bg-gray-700 mb-10"></div>

                    {{-- DYNAMIC OPTIONS SECTION --}}
                    <div x-data="{ options: ['', ''] }">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest border-l-4 border-indigo-500 pl-3">Poll Options</h3>
                            <span class="text-[10px] font-bold text-gray-400 uppercase" x-text="options.length + ' Options Total'"></span>
                        </div>
                        
                        <div class="space-y-4">
                            <template x-for="(option, index) in options" :key="index">
                                <div class="group flex items-center gap-3">
                                    <div class="flex-none w-10 h-10 bg-gray-100 dark:bg-gray-900 rounded-xl flex items-center justify-center text-[10px] font-black text-gray-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors" x-text="index + 1">
                                    </div>
                                    
                                    <input type="text" 
                                           :name="'options[]'" 
                                           class="flex-1 bg-gray-50 dark:bg-gray-900 border-none rounded-xl py-3 px-5 text-sm font-bold text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 shadow-inner" 
                                           :placeholder="'Enter choice ' + (index + 1) + '...'" 
                                           required>
                                    
                                    <button type="button" 
                                            x-show="options.length > 2" 
                                            @click="options.splice(index, 1)" 
                                            class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <button type="button" 
                                @click="options.push('')" 
                                class="mt-6 flex items-center gap-2 px-6 py-3 bg-gray-100 dark:bg-gray-900/50 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-xl transition-all border-2 border-dashed border-gray-200 dark:border-gray-700 w-full justify-center group">
                            <svg class="w-4 h-4 group-hover:scale-125 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span class="text-[10px] font-black uppercase tracking-widest">Add Another Choice</span>
                        </button>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="mt-12">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 rounded-2xl uppercase text-xs tracking-[0.2em] transition-all shadow-xl shadow-indigo-500/20 transform hover:-translate-y-1">
                            Launch Poll Now
                        </button>
                        <p class="text-center text-[10px] text-gray-400 font-bold uppercase mt-4 tracking-widest">By clicking launch, your poll will be visible to all users.</p>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>