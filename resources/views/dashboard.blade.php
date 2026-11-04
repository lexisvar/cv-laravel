<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Last 30 days --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ __('Last 30 days') }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach ([
                        ['People (unique)', $summary['visitors'], 'Different visitors, bots excluded'],
                        ['Page views', $summary['views'], 'Home page, bots excluded'],
                        ['CV opened', $summary['cv'], 'PDF views, downloads and plain CV'],
                        ['Link previews', $summary['previews'], 'Your link was shared in an app'],
                        ['Bots filtered', $summary['bots'], 'Crawlers, scripts, monitors'],
                    ] as [$label, $value, $hint])
                        <div class="bg-white shadow-sm sm:rounded-lg p-4">
                            <div class="text-2xl font-semibold text-gray-900">{{ number_format($value) }}</div>
                            <div class="text-sm font-medium text-gray-700">{{ $label }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $hint }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                @foreach ([
                    ['Where people came from', $sources, 'source'],
                    ['Shared in', $previewSources, 'agent'],
                    ['Devices', $devices, 'device'],
                ] as [$title, $rows, $field])
                    <div class="bg-white shadow-sm sm:rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ $title }}</h4>
                        @forelse ($rows as $row)
                            <div class="flex justify-between text-sm py-1 border-b border-gray-100 last:border-0">
                                <span class="text-gray-700">{{ match ($field) { 'device' => ucfirst($row->device ?: 'unknown'), 'source' => $row->source === 'direct' ? 'Direct (typed or bookmarked)' : $row->source, default => $row->{$field} ?: 'unknown' } }}</span>
                                <span class="text-gray-900 font-medium">{{ $row->total }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">{{ __('No data yet.') }}</p>
                        @endforelse
                    </div>
                @endforeach
            </div>

            {{-- Visit log --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <h3 class="font-semibold text-lg text-gray-800">{{ __('Visits') }}</h3>
                        <nav class="flex gap-1 text-sm" aria-label="Filter visits">
                            @foreach ($tabs as $key => $label)
                                <a href="{{ route('dashboard', ['type' => $key]) }}"
                                   class="px-3 py-1.5 rounded-md {{ $tab === $key ? 'bg-gray-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">{{ $label }}</a>
                            @endforeach
                        </nav>
                    </div>
                    <p class="text-xs text-gray-500 mb-3">{{ __('Your own visits are not counted while you are logged in. IP addresses are stored masked (last part removed).') }}</p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wider text-left">
                                <tr>
                                    <th class="py-3 px-3">Date</th>
                                    <th class="py-3 px-3">Page</th>
                                    <th class="py-3 px-3">{{ $tab === 'human' ? 'Source' : 'Who' }}</th>
                                    <th class="py-3 px-3">Device</th>
                                    <th class="py-3 px-3">Visitor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($visits as $visit)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50" title="{{ $visit->uagent }}">
                                        <td class="py-3 px-3 whitespace-nowrap text-gray-700">{{ $visit->created_at?->timezone('Australia/Sydney')->format('d M Y, H:i') }}</td>
                                        <td class="py-3 px-3 text-gray-900">{{ $visit->pageLabel() }}</td>
                                        <td class="py-3 px-3 text-gray-700">
                                            @if ($visit->type === 'human')
                                                {{ $visit->referrer ?: 'Direct' }}
                                            @else
                                                <span class="inline-block px-2 py-0.5 rounded text-xs {{ $visit->type === 'preview' ? 'bg-teal-50 text-teal-800' : 'bg-gray-100 text-gray-600' }}">{{ $visit->agent ?: ucfirst($visit->type) }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-gray-700 whitespace-nowrap">{{ ucfirst($visit->device ?? '') }}@if ($visit->browser) · {{ $visit->browser }}@endif @if ($visit->os) · {{ $visit->os }}@endif</td>
                                        <td class="py-3 px-3 text-gray-500 font-mono text-xs">{{ $visit->ip }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="py-6 px-3 text-center text-gray-500">{{ __('No visits in this list yet.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $visits->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
