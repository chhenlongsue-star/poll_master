<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Manage Poll Categories') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-8 border-b pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Create New Category') }}</h3>
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="flex items-center gap-4">
                        @csrf
                        <div class="flex-1">
                            <x-text-input name="name" type="text" class="block w-full" placeholder="Category name..." required />
                        </div>
                        <x-primary-button>{{ __('Add Category') }}</x-primary-button>
                    </form>
                </div>

                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-3 border-b">Name</th>
                            <th class="px-4 py-3 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($categories as $category)
                        <tr>
                            <td class="px-4 py-4 font-medium">{{ $category->name }}</td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end items-center">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">{{ __('Edit') }}</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 font-bold">{{ __('Delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>