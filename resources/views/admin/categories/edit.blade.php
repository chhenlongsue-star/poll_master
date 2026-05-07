<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 dark:text-white leading-tight uppercase tracking-widest">
            {{ __('Edit Category') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl p-8 border dark:border-gray-700">
                
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-indigo-400">Category Details</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Modify the name of the category below.</p>
                </div>

                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-8">
                        <label for="name" class="block text-[10px] font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2">
                            Category Name
                        </label>
                        <input type="text" name="name" id="name" value="{{ $category->name }}" 
                               class="block w-full border-gray-200 dark:border-gray-700 rounded-lg shadow-sm 
                                      bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white
                                      focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                        @error('name')
                            <p class="mt-2 text-xs text-red-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4 border-t dark:border-gray-700 pt-6">
                        <a href="{{ route('admin.categories.index') }}" 
                           class="text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white transition">
                            Cancel
                        </a>
                        
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-black text-[10px] text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-lg shadow-indigo-500/20">
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>