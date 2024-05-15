<section>
  <header>
      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Key Information') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
          {{ __("Update your key information.") }}
      </p>
  </header>

  <form method="post" action="{{ route('general.create') }}" class="mt-6 space-y-6">
      @csrf
      @method('post')

      <div>
          <x-input-label for="key" :value="__('Key')" />
          <x-text-input id="key" name="key" type="text" class="mt-1 block w-full" required autofocus autocomplete="key" />
          <x-input-error class="mt-2" :messages="$errors->get('key')" />
      </div>

      <div>
          <x-input-label for="value" :value="__('Value')" />
          <x-text-input id="value" name="value" type="text" class="mt-1 block w-full" required autocomplete="value" />
          <x-input-error class="mt-2" :messages="$errors->get('value')" />
      </div>

      <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Save') }}</x-primary-button>
      </div>
  </form>
</section>
