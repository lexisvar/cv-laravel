<section>
  <header>
      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Skill Information') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
          {{ __("Update your skill information.") }}
      </p>
  </header>          

  <form method="post" action="{{ route('skill.update', $skill->id) }}" class="mt-6 space-y-6">
      @csrf
      @method('patch')

      <div>
          <x-input-label for="name" :value="__('Name')" />
          <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $skill->name)" required autofocus autocomplete="name" />
          <x-input-error class="mt-2" :messages="$errors->get('name')" />
      </div>

      <div>
          <x-input-label for="percent" :value="__('Percent')" />
          <x-text-input id="percent" name="percent" type="number" class="mt-1 block w-full" :value="old('percent', $skill->percent)" required autocomplete="percent" />
          <x-input-error class="mt-2" :messages="$errors->get('percent')" />
      </div>

      <div class="flex items-center gap-4">
          <x-primary-button>{{ __('Save') }}</x-primary-button>
          @if (session('status') === 'skill-updated')
              <p
                  x-data="{ show: true }"
                  x-show="show"
                  x-transition
                  x-init="setTimeout(() => show = false, 2000)"
                  class="text-sm text-gray-600"
              >{{ __('Skill Saved.') }}</p>
          @endif
      </div>
  </form>
</section>