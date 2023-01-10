<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Education') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your education is deleted, all of its resources and data will be permanently deleted.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-education-deletion')"
    >{{ __('Delete Education') }}</x-danger-button>

    <x-modal name="confirm-education-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('education.destroy', $education->id) }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your education?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your education is deleted, all of its resources and data will be permanently deleted.') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Delete Education') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
