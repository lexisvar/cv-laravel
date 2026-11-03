@props(['text' => ''])
{{--
  Renders plain text safely: blank lines separate paragraphs, and lines that
  start with "- " or "* " become bullet points. All content is escaped.
--}}
@php
    $blocks = [];
    foreach (preg_split('/\R/', trim((string) $text)) as $line) {
        $line = trim($line);
        if ($line === '') {
            $blocks[] = ['type' => 'break'];
        } elseif (preg_match('/^[-*•]\s+(.*)$/u', $line, $m)) {
            $last = end($blocks);
            if ($last && $last['type'] === 'list') {
                $blocks[key($blocks)]['items'][] = $m[1];
            } else {
                $blocks[] = ['type' => 'list', 'items' => [$m[1]]];
            }
        } else {
            $last = end($blocks);
            if ($last && $last['type'] === 'para') {
                $blocks[key($blocks)]['lines'][] = $line;
            } else {
                $blocks[] = ['type' => 'para', 'lines' => [$line]];
            }
        }
    }
@endphp
<div {{ $attributes->merge(['class' => 'rich-text']) }}>
    @foreach ($blocks as $block)
        @if ($block['type'] === 'para')
            <p>{!! implode('<br>', array_map('e', $block['lines'])) !!}</p>
        @elseif ($block['type'] === 'list')
            <ul>
                @foreach ($block['items'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        @endif
    @endforeach
</div>
