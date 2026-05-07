<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-xl text-gray-800 dark:text-white leading-tight uppercase tracking-widest">
            {{ __('Manage Poll Categories') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500/10 border-l-4 border-green-500 text-green-600 dark:text-green-400 font-bold rounded-r-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-xl p-8 border dark:border-gray-700">
                
                {{-- Create Category Section --}}
                <div class="mb-10 border-b dark:border-gray-700 pb-8">
                    <h3 class="text-sm font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-4">
                        {{ __('Create New Category') }}
                    </h3>
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="flex items-center gap-4">
                        @csrf
                        <div class="flex-1">
                            <x-text-input name="name" type="text" 
                                class="block w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500" 
                                placeholder="Enter category name..." required />
                        </div>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-lg shadow-indigo-500/20 transition">
                            {{ __('Add Category') }}
                        </button>
                    </form>
                </div>

                {{-- Categories Table --}}
                <div class="overflow-hidden rounded-xl border dark:border-gray-700">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-4 border-b dark:border-gray-700">Category Name</th>
                                <th class="px-6 py-4 border-b dark:border-gray-700 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($categories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    {{ $category->name }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end items-center gap-4">
                                        <a href="{{ route('admin.categories.edit', $category) }}" 
                                           class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ __('Edit') }}
                                        </a>
                                        
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-red-700">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($categories->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-gray-500 dark:text-gray-400 text-sm italic">No categories found. Create your first one above.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>