<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a New Poll') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <form action="{{ route('polls.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <x-input-label for="title" :value="__('Poll Question / Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus placeholder="e.g., What is your favorite programming language?" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="category_id" :value="__('Category')" />
                        <select name="category_id" id="category_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="description" :value="__('Description (Optional)')" />
                        <textarea name="description" id="description" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" placeholder="Add some context..."></textarea>
                    </div>

                    <hr class="my-8">

                    <div x-data="{ options: ['', ''] }">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Poll Options</h3>
                        
                        <template x-for="(option, index) in options" :key="index">
                            <div class="flex items-center gap-2 mb-3">
                                <input type="text" 
                                       :name="'options[]'" 
                                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" 
                                       :placeholder="'Option ' + (index + 1)" 
                                       required>
                                
                                <button type="button" 
                                        x-show="options.length > 2" 
                                        @click="options.splice(index, 1)" 
                                        class="text-red-500 hover:text-red-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </template>

                        <button type="button" 
                                @click="options.push('')" 
                                class="mt-2 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            + Add Option
                        </button>
                    </div>

                    <div class="flex items-center justify-end mt-10">
                        <x-primary-button class="ms-3">
                            {{ __('Launch Poll') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>