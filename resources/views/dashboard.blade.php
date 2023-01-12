<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                  <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3">
                    {{ __('Visits') }}
                  </h2>
                  <table class="w-full leading-normal ">
                    <thead
                      class="text-gray-600 text-xs font-semibold border-gray tracking-wider text-left px-5 py-3 bg-gray-100 hover:cursor-pointer uppercase border-b-2 border-gray-200">
                      <tr class="border-b border-gray">
                        <th scope="col"
                          class="text-gray-dark border-gray border-b-2 border-t-2 border-gray-200 py-3 px-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                          
                          IP
                        </th>
                        <th scope="col"
                          class="text-gray-dark border-gray border-b-2 border-t-2 border-gray-200 py-3 px-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                          
                          Controller
                        </th>
                        <th scope="col"
                          class="text-gray-dark border-gray border-b-2 border-t-2 border-gray-200 py-3 px-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                          <!---->
                          Function
                        </th>
                        <th scope="col"
                          class="text-gray-dark border-gray border-b-2 border-t-2 border-gray-200 py-3 px-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                          <!---->
                          User Agent
                        </th>
                        <th scope="col"
                          class="text-gray-dark border-gray border-b-2 border-t-2 border-gray-200 py-3 px-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                          <!---->
                          Date
                        </th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($visits as $visit)
                        <tr class="hover:bg-gray-100 hover:cursor-pointer">
                          <td class="py-4 px-6 border-b border-gray-200 text-gray-900 text-sm ">
                            <div class="flex items-center" classes="[object Object]">
                              
                              <div class="ml-3">
                                <p class="text-gray-900 whitespace-no-wrap">{{$visit->ip}}</p>
                              </div>
                            </div>
                          </td>
                          <td class="py-4 px-6 border-b border-gray-200 text-gray-900 text-sm ">
                            <span>{{$visit->controller}}</span>
                          </td>
                          <td class="py-4 px-6 border-b border-gray-200 text-gray-900 text-sm ">
                            <span>{{$visit->function}}</span>
                          </td>
                          <td class="py-4 px-6 border-b border-gray-200 text-gray-900 text-sm ">
                            <span>{{$visit->uagent}}</span>
                          </td>
                          <td class="py-4 px-6 border-b border-gray-200 text-gray-900 text-sm ">
                            <span>{{$visit->created_at}}</span>
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
