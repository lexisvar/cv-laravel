<section>
  <header>
      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Skill Information') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
          {{ __("Update your skill information.") }}
      </p>
  </header>          

  <form method="post" action="{{ route('skill.create') }}" class="mt-6 space-y-6">
      @csrf
      @method('post')

      <div>
          <x-input-label for="name" :value="__('Name')" />
          <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
          <x-input-error class="mt-2" :messages="$errors->get('name')" />
      </div>

      <div>
          <x-input-label for="category" :value="__('Category (e.g. Backend, Frontend, Data, DevOps)')" />
          <x-text-input id="category" name="category" type="text" class="mt-1 block w-full" :value="old('category')" />
          <x-input-error class="mt-2" :messages="$errors->get('category')" />
      </div>

      <div>
          <x-input-label for="sort_order" :value="__('Display order (0 first)')" />
          <x-text-input id="sort_order" name="sort_order" type="number" min="0" class="mt-1 block w-full" :value="old('sort_order')" />
          <x-input-error class="mt-2" :messages="$errors->get('sort_order')" />
      </div>

      <div>
          <x-input-label for="percent" :value="__('Percent (optional, not shown publicly)')" />
          <x-text-input id="percent" name="percent" type="number" class="mt-1 block w-full" autocomplete="percent" />
          <x-input-error class="mt-2" :messages="$errors->get('percent')" />
      </div>

      <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Save') }}</x-primary-button>
      </div>
  </form>
</section>