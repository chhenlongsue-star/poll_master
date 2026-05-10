<x-app-layout>
    <x-slot name="header">
        {{-- Added dark:text-white --}}
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    {{-- 1. Outer Wrapper: Added dark:bg-gray-950 to match your dashboard --}}
    <div class="py-12 bg-gray-100 dark:bg-gray-950 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- 2. Info Card: Added dark:bg-gray-900 and dark:border-gray-800 --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg border border-transparent dark:border-gray-800">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- 3. Delete Card: Added dark:bg-gray-900 --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg border-t-4 border-red-500">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>