@php
    $g = fn (string $key, string $default = '') => $generals[$key] ?? $default;
    $name = $g('name', 'Alexis Vargas');
    $period = fn ($start, $finish) => ($start ? date('M Y', strtotime($start)) : '')
        .($start ? ' – ' : '')
        .($finish ? date('M Y', strtotime($finish)) : 'Present');
    $compactBefore = (int) $g('compact_before', '0');
    $isCompact = fn ($finish) => $compactBefore && $finish && (int) date('Y', strtotime($finish)) < $compactBefore;
    $site = preg_replace('#^https?://#', '', rtrim(url('/'), '/'));
    $strip = fn ($url) => preg_replace('#^https?://(www\.)?#', '', rtrim((string) $url, '/'));
    $github = $g('github', 'https://github.com/lexisvar');
    $linkedin = $g('linkedin', 'https://www.linkedin.com/in/lexisvar');
    $detailed = $experiences->reject(fn ($e) => $isCompact($e->finish_date));
    $earlier = $experiences->filter(fn ($e) => $isCompact($e->finish_date));
@endphp
<!DOCTYPE html>
<html lang="en-AU">
<head>
    <meta charset="UTF-8">
    <title>{{ $name }} – CV</title>
    <meta name="author" content="{{ $name }}">
    <meta name="subject" content="{{ $g('occupation') }}">
    <meta name="keywords" content="{{ $skillGroups->flatten()->pluck('name')->implode(', ') }}">
    {{-- Self-contained styles for dompdf (no flexbox/grid) and for /simple in a browser. --}}
    <style>
        @page { margin: 34px 42px 44px; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 9.6pt; line-height: 1.42; color: #1f2937; margin: 0; }
        a { color: #0f766e; text-decoration: none; }
        table { border-collapse: collapse; width: 100%; }
        td { vertical-align: top; padding: 0; }

        .head td { vertical-align: bottom; }
        h1 { font-size: 21pt; margin: 0; color: #0f172a; letter-spacing: -0.3px; }
        .headline { font-size: 11pt; color: #0f766e; margin-top: 2px; }
        .contact { text-align: right; font-size: 8.8pt; color: #475569; line-height: 1.55; }
        .rule { border-bottom: 1.5px solid #0f766e; margin: 10px 0 2px; }
        .rights { font-size: 8.8pt; color: #475569; margin-top: 4px; }

        h2 { page-break-after: avoid; font-size: 9pt; text-transform: uppercase; letter-spacing: 1.2px; color: #0f766e; margin: 15px 0 6px; padding-bottom: 3px; border-bottom: 0.6px solid #cbd5e1; }
        h3 { font-size: 10.2pt; margin: 0; color: #0f172a; }
        p { margin: 0 0 4px; }
        ul { margin: 2px 0 2px 13px; padding: 0; }
        li { margin-bottom: 1.5px; }
        .rich-text p:last-child, .rich-text ul:last-child { margin-bottom: 0; }

        .entry { margin-bottom: 9px; page-break-inside: avoid; }
        .entry-head td.date { text-align: right; white-space: nowrap; color: #475569; font-size: 8.8pt; width: 120px; }
        .org { color: #475569; margin: 0 0 3px; }
        .stack { color: #475569; font-size: 8.6pt; margin-top: 2px; }
        .stack b { color: #334155; }

        .skills td { padding: 1.5px 0; }
        .skills td.cat { font-weight: bold; width: 125px; color: #0f172a; }

        .earlier td { padding: 1.5px 0; font-size: 9pt; }
        .earlier td.date { text-align: right; color: #475569; white-space: nowrap; width: 120px; }

        @media screen {
            body { max-width: 820px; margin: 32px auto; padding: 0 20px; font-size: 13px; }
        }
    </style>
</head>
<body>
    <table class="head">
        <tr>
            <td>
                <h1>{{ $name }}</h1>
                <div class="headline">{{ $g('occupation') }}</div>
            </td>
            <td class="contact">
                @if ($g('address')){{ $g('address') }}<br>@endif
                @if ($g('email'))<a href="mailto:{{ $g('email') }}">{{ $g('email') }}</a>@endif
                @if ($g('phone')) · {{ $g('phone') }}@endif<br>
                <a href="{{ url('/') }}">{{ $site }}</a> ·
                <a href="{{ $github }}">{{ $strip($github) }}</a> ·
                <a href="{{ $linkedin }}">LinkedIn</a>
            </td>
        </tr>
    </table>
    <div class="rule"></div>
    @if ($g('work_rights'))
        <div class="rights">{{ $g('work_rights') }}</div>
    @endif

    @if ($g('biography'))
        <h2>Summary</h2>
        <x-rich-text :text="$g('pdf_summary', $g('biography'))" />
    @endif

    @if ($skillGroups->isNotEmpty())
        <h2>Technical Skills</h2>
        <table class="skills">
            @foreach ($skillGroups as $category => $skills)
                <tr>
                    <td class="cat">{{ $category }}</td>
                    <td>{{ $skills->pluck('name')->implode(' · ') }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <h2>Experience</h2>
    @foreach ($detailed as $experience)
        <div class="entry">
            <table class="entry-head">
                <tr>
                    <td><h3>{{ $experience->occupation }}</h3></td>
                    <td class="date">{{ $period($experience->start_date, $experience->finish_date) }}</td>
                </tr>
            </table>
            <div class="org">{{ $experience->company }}</div>
            <x-rich-text :text="$experience->description" />
        </div>
    @endforeach

    @if ($earlier->isNotEmpty())
        <h2>Earlier Experience</h2>
        <table class="earlier">
            @foreach ($earlier as $experience)
                <tr>
                    <td><b>{{ $experience->occupation }}</b>, {{ $experience->company }}</td>
                    <td class="date">{{ $period($experience->start_date, $experience->finish_date) }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if ($projects->isNotEmpty())
        <h2>Projects</h2>
        @foreach ($projects as $project)
            <div class="entry">
                <table class="entry-head">
                    <tr>
                        <td><h3>{{ $project->name }}@if ($project->role) <span style="font-weight: normal; color: #475569;">· {{ $project->role }}</span>@endif</h3></td>
                        <td class="date">@if ($project->start_date){{ $period($project->start_date, $project->finish_date) }}@endif</td>
                    </tr>
                </table>
                <x-rich-text :text="$project->description" />
                @if ($project->stackList() || $project->url || $project->repo_url)
                    <div class="stack">
                        @if ($project->stackList())<b>Stack:</b> {{ implode(', ', $project->stackList()) }}@endif
                        @if ($project->url) · <a href="{{ $project->url }}">{{ $strip($project->url) }}</a>@endif
                        @if ($project->repo_url) · <a href="{{ $project->repo_url }}">{{ $strip($project->repo_url) }}</a>@endif
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    <div style="page-break-inside: avoid;">
    <h2>Education</h2>
    @foreach ($educations as $education)
        <div class="entry">
            <table class="entry-head">
                <tr>
                    <td><h3>{{ $education->study_name }}</h3></td>
                    <td class="date">{{ $period($education->start_date, $education->finish_date) }}</td>
                </tr>
            </table>
            <div class="org">{{ $education->college }}@if ($education->study_title) · {{ $education->study_title }}@endif</div>
            @if ($g('pdf_education_descriptions', '0') === '1')
                <x-rich-text :text="$education->description" />
            @endif
        </div>
    @endforeach
    </div>
</body>
</html>
