@php($project = $project ?? null)
<section>
  <header>
    <h2 class="text-lg font-medium text-gray-900">{{ __('Project Information') }}</h2>
    <p class="mt-1 text-sm text-gray-600">
      {{ __('Description supports bullet points: start a line with "- " to render it as a list item.') }}
    </p>
  </header>

  <form method="post"
        action="{{ $project ? route('project.update', $project->id) : route('project.create') }}"
        class="mt-6 space-y-6">
    @csrf
    @method($project ? 'patch' : 'post')

    @foreach ([
      'name' => ['Name', 'text', true],
      'role' => ['Role (e.g. Founder & Lead Engineer)', 'text', false],
      'url' => ['Live URL', 'url', false],
      'repo_url' => ['Repository URL', 'url', false],
      'stack' => ['Stack (comma separated)', 'text', false],
      'start_date' => ['Start Date', 'date', false],
      'finish_date' => ['Finish Date (empty = ongoing)', 'date', false],
      'sort_order' => ['Display order (0 first)', 'number', false],
    ] as $field => [$label, $type, $required])
      <div>
        <x-input-label :for="$field" :value="__($label)" />
        <x-text-input :id="$field" :name="$field" :type="$type" class="mt-1 block w-full"
                      :value="old($field, $project?->{$field})" :required="$required" />
        <x-input-error class="mt-2" :messages="$errors->get($field)" />
      </div>
    @endforeach

    <div>
      <x-input-label for="description" :value="__('Description')" />
      <textarea id="description" name="description" rows="10" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ old('description', $project?->description) }}</textarea>
      <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <div class="flex items-center gap-4">
      <x-primary-button>{{ __('Save') }}</x-primary-button>
      @if (session('status') === 'project-updated')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
           class="text-sm text-gray-600">{{ __('Project Saved.') }}</p>
      @endif
    </div>
  </form>
</section>
