<section>
  <header>
      <h2 class="text-lg font-medium text-gray-900">
          {{ __('Education Information') }}
      </h2>

      <p class="mt-1 text-sm text-gray-600">
          {{ __("Update your education information.") }}
      </p>
  </header>          

  <form method="post" action="{{ route('education.update', $education->id) }}" class="mt-6 space-y-6">
      @csrf
      @method('patch')

      <div>
          <x-input-label for="study_name" :value="__('Study Name')" />
          <x-text-input id="study_name" name="study_name" type="text" class="mt-1 block w-full" :value="old('study_name', $education->study_name)" required autofocus autocomplete="study_name" />
          <x-input-error class="mt-2" :messages="$errors->get('study_name')" />
      </div>

      <div>
        <x-input-label for="study_title" :value="__('Study Title')" />
        <x-text-input id="study_title" name="study_title" type="text" class="mt-1 block w-full" :value="old('study_title', $education->study_title)" required autocomplete="study_title" />
        <x-input-error class="mt-2" :messages="$errors->get('study_title')" />
      </div>

      <div>
          <x-input-label for="college" :value="__('College')" />
          <x-text-input id="college" name="college" type="text" class="mt-1 block w-full" :value="old('college', $education->college)" required autocomplete="college" />
          <x-input-error class="mt-2" :messages="$errors->get('college')" />
      </div>

      <div>
        <x-input-label for="start_date" :value="__('Start Date')" />
        <x-text-input id="start_date" name="start_date" type="text" class="mt-1 block w-full" :value="old('start_date', $education->start_date)" required autocomplete="start_date" />
        <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
      </div>

      <div>
        <x-input-label for="finish_date" :value="__('Finish Date')" />
        <x-text-input id="finish_date" name="finish_date" type="text" class="mt-1 block w-full" :value="old('finish_date', $education->finish_date)" required autocomplete="finish_date" />
        <x-input-error class="mt-2" :messages="$errors->get('finish_date')" />
      </div>

      <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" type="text"  cols='6' rows='6' class="mt-1 block w-full" required autocomplete="description" >{{$education->description}}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
      </div>

      <div class="flex items-center gap-4">
          <x-primary-button>{{ __('Save') }}</x-primary-button>
          @if (session('status') === 'education-updated')
              <p
                  x-data="{ show: true }"
                  x-show="show"
                  x-transition
                  x-init="setTimeout(() => show = false, 2000)"
                  class="text-sm text-gray-600"
              >{{ __('Education Saved.') }}</p>
          @endif
      </div>
  </form>
</section>