<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Projects') }}</h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <a href="{{ route('project.new') }}">
            <x-primary-button>{{ __('New Project') }}</x-primary-button>
          </a>
          <table class="w-full leading-normal mt-4">
            <thead class="bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              <tr>
                <th class="py-3 px-3">Order</th>
                <th class="py-3 px-3">Name</th>
                <th class="py-3 px-3">Role</th>
                <th class="py-3 px-3">Stack</th>
                <th class="py-3 px-3">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($projects as $project)
                <tr class="hover:bg-gray-100 border-b border-gray-200 text-sm">
                  <td class="py-4 px-3">{{ $project->sort_order }}</td>
                  <td class="py-4 px-3">{{ $project->name }}</td>
                  <td class="py-4 px-3">{{ $project->role }}</td>
                  <td class="py-4 px-3">{{ $project->stack }}</td>
                  <td class="py-4 px-3">
                    <a class="underline" href="{{ route('project.edit', $project->id) }}">{{ __('Edit') }}</a>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="py-4 px-3 text-sm text-gray-600">{{ __('No projects yet.') }}</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
