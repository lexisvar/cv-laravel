<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Project') }}</h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
          @include('projects.partials.project-form', ['project' => $project])
        </div>
      </div>

      <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
          <section class="space-y-6">
            <header>
              <h2 class="text-lg font-medium text-gray-900">{{ __('Delete Project') }}</h2>
            </header>
            <form method="post" action="{{ route('project.destroy', $project->id) }}"
                  onsubmit="return confirm('Delete this project permanently?');">
              @csrf
              @method('delete')
              <x-danger-button>{{ __('Delete Project') }}</x-danger-button>
            </form>
          </section>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
