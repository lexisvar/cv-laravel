<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Key') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your key is deleted, all of its resources and data will be permanently deleted.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-key-deletion')"
    >{{ __('Delete Key') }}</x-danger-button>

    <x-modal name="confirm-key-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('general.destroy', $general->id) }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your key?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your key is deleted, all of its resources and data will be permanently deleted.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Delete Key') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
