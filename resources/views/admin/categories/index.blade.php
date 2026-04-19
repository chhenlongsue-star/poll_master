<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Poll Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-8 border-b pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Create New Category') }}</h3>
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="flex items-center gap-4">
                        @csrf
                        <div class="flex-1">
                            <x-text-input id="name" name="name" type="text" class="block w-full" placeholder="Enter category name..." required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <x-primary-button>
                            {{ __('Add Category') }}
                        </x-primary-button>
                    </form>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Existing Categories') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-gray-600 uppercase font-semi-bold">
                                <tr>
                                    <th class="px-4 py-3 border-b">Category Name</th>
                                    <th class="px-4 py-3 border-b text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($categories as $category)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 font-medium text-gray-900">
                                            {{ $category->name }}
                                        </td>
                                        <td class="px-4 py-4 text-right">
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category? It might affect polls assigned to it.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-gray-500 italic">
                                            No categories found. Create your first one above!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>