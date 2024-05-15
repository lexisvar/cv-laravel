<section>
  <header>
      <h2 class="text-lg font-medium text-gray-900">
          {{ __('key Information') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
          {{ __("Update your key information.") }}
      </p>
  </header>

  <form method="post" action="{{ route('general.update', $general->id) }}" class="mt-6 space-y-6">
      @csrf
      @method('patch')

      <div>
          <x-input-label for="key" :value="__('Key')" />
          <x-text-input id="key" name="key" type="text" class="mt-1 block w-full" :value="old('name', $general->key)" required autofocus autocomplete="key" />
          <x-input-error class="mt-2" :messages="$errors->get('name')" />
      </div>

      <div>
          <x-input-label for="value" :value="__('Value')" />
          <x-text-input id="value" name="value" type="text" class="mt-1 block w-full" :value="old('value', $general->value)" required autocomplete="value" />
          <x-input-error class="mt-2" :messages="$errors->get('value')" />
      </div>

      <div class="flex items-center gap-4">
          <x-primary-button>{{ __('Save') }}</x-primary-button>
          @if (session('status') === 'general-updated')
              <p
                  x-data="{ show: true }"
                  x-show="show"
                  x-transition
                  x-init="setTimeout(() => show = false, 2000)"
                  class="text-sm text-gray-600"
              >{{ __('Key Saved.') }}</p>
          @endif
      </div>
  </form>
</section>
