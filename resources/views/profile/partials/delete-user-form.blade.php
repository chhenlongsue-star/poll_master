<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-gray-900 uppercase tracking-widest">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-gray-900">
                {{ __('Final Confirmation') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Are you absolutely sure? This action cannot be undone.') }}
            </p>

            {{-- Show password field ONLY if the user is NOT a Google user --}}
            @if(is_null(Auth::user()->google_id))
                <div class="mt-6">
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Enter password to confirm') }}"
                    />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>
            @else
                <div class="mt-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 text-xs font-bold uppercase">
                    Confirming deletion for Google-linked account: {{ Auth::user()->email }}
                </div>
            @endif

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Permanently Delete') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>