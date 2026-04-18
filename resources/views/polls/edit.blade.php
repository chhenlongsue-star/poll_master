<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Poll') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow-sm">
                
                <form action="{{ route('polls.update', $poll) }}" method="POST">
                    @csrf
                    @method('PUT') <div class="mb-6">
                        <x-input-label for="title" :value="__('Poll Question')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="$poll->title" required />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="category_id" :value="__('Category')" />
                        <select name="category_id" id="category_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $poll->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea name="description" id="description" rows="3" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ $poll->description }}</textarea>
                    </div>

                    <div class="flex items-center justify-between mt-10">
                        <x-primary-button>
                            {{ __('Update Poll') }}
                        </x-primary-button>
                        
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:underline">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>