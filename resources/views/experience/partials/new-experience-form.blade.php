<section>
  <header>
      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Experience Information') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
          {{ __("Update your experience information.") }}
      </p>
  </header>          

  <form method="post" action="{{ route('experience.create') }}" class="mt-6 space-y-6">
      @csrf
      @method('post')

      <div>
        <x-input-label for="occupation" :value="__('Occupation')" />
        <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full" required autofocus autocomplete="occupation" />
        <x-input-error class="mt-2" :messages="$errors->get('occupation')" />
    </div>

    <div>
        <x-input-label for="company" :value="__('Company')" />
        <x-text-input id="company" name="company" type="text" class="mt-1 block w-full" required autocomplete="company" />
        <x-input-error class="mt-2" :messages="$errors->get('company')" />
    </div>

    <div>
      <x-input-label for="start_date" :value="__('Start Date')" />
      <x-text-input id="start_date" name="start_date" type="text" class="mt-1 block w-full" required autocomplete="start_date" />
      <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
    </div>

    <div>
      <x-input-label for="finish_date" :value="__('Finish Date')" />
      <x-text-input id="finish_date" name="finish_date" type="text" class="mt-1 block w-full" required autocomplete="finish_date" />
      <x-input-error class="mt-2" :messages="$errors->get('finish_date')" />
    </div>

    <div>
      <x-input-label for="description" :value="__('Description')" />
      <textarea id="description" name="description" type="text"  cols='6' rows='6' class="mt-1 block w-full" required autocomplete="description" ></textarea>
      <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

      <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Save') }}</x-primary-button>
      </div>
  </form>
</section>