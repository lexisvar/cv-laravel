<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Education') }}
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            
              <div class="p-6 text-gray-900">
                <a href="{{ route('education.new') }}">
                  <x-primary-button >
                    {{ __('New Education') }}
                  </x-primary-button>
                </a>
                <table class="w-full leading-normal ">
                  <thead
                    class="text-gray-600 text-xs font-semibold border-gray tracking-wider text-left px-5 py-3 bg-gray-100 hover:cursor-pointer uppercase border-b-2 border-gray-200">
                    <tr class="border-b border-gray">
                      <th scope="col" class="text-gray-dark border-gray border-b-2 border-t-2 border-gray-200 py-3 px-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Name
                      </th>
                      <th scope="col" class="text-gray-dark border-gray border-b-2 border-t-2 border-gray-200 py-3 px-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Title
                      </th>
                      <th scope="col" class="text-gray-dark border-gray border-b-2 border-t-2 border-gray-200 py-3 px-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        College
                      </th>
                      <th scope="col" class="text-gray-dark border-gray border-b-2 border-t-2 border-gray-200 py-3 px-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Start Date
                      </th>
                      <th scope="col" class="text-gray-dark border-gray border-b-2 border-t-2 border-gray-200 py-3 px-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Finish Date
                      </th>
                      <th scope="col"
                        class="text-gray-dark border-gray border-b-2 border-t-2 border-gray-200 py-3 px-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <!---->
                        Actions
                      </th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($educations as $education)
                      <tr class="hover:bg-gray-100 hover:cursor-pointer">
                        <td class="py-4 px-6 border-b border-gray-200 text-gray-900 text-sm ">                            
                          <div class="ml-3">
                            <p class="text-gray-900 whitespace-no-wrap">{{$education->study_name}}</p>
                          </div>
                        </td>
                        <td class="py-4 px-6 border-b border-gray-200 text-gray-900 text-sm ">
                          <span>{{$education->study_title}}</span>
                        </td>
                        <td class="py-4 px-6 border-b border-gray-200 text-gray-900 text-sm ">
                          <span>{{$education->college}}</span>
                        </td>
                        <td class="py-4 px-6 border-b border-gray-200 text-gray-900 text-sm ">
                          <span>{{$education->start_date}}</span>
                        </td>
                        <td class="py-4 px-6 border-b border-gray-200 text-gray-900 text-sm ">
                          <span>{{$education->finish_date}}</span>
                        </td>
                        <td class="py-4 px-6 border-b border-gray-200 text-gray-900 text-sm ">
                          <a href="{{ route('education.edit', $education->id) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                          </a>
                        </td>
                        
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
          </div>
      </div>
  </div>
</x-app-layout>
