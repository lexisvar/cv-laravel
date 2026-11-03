@php
    $g = fn (string $key, string $default = '') => $generals[$key] ?? $default;
    $name = $g('name', 'Alexis Vargas');
    $period = fn ($start, $finish) => ($start ? date('M Y', strtotime($start)) : '')
        .($start ? ' – ' : '')
        .($finish ? date('M Y', strtotime($finish)) : 'Present');
@endphp
<!DOCTYPE html>
<html lang="en-AU">
<head>
    <meta charset="UTF-8">
    <title>{{ $name }} – CV</title>
    {{-- Self-contained styles: single column, ATS-friendly, no remote assets. --}}
    <style>
        @page { margin: 28px 36px; }
        body { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; font-size: 10.5px; line-height: 1.45; color: #222; margin: 0; }
        a { color: #0b5394; text-decoration: none; }
        h1 { font-size: 22px; margin: 0; }
        h2 { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #0b5394; border-bottom: 1px solid #0b5394; padding-bottom: 2px; margin: 16px 0 8px; }
        h3 { font-size: 11.5px; margin: 0; }
        p { margin: 0 0 4px; }
        ul { margin: 2px 0 4px 14px; padding: 0; }
        li { margin-bottom: 2px; }
        .headline { font-size: 12.5px; color: #444; margin-top: 2px; }
        .contact { margin-top: 6px; color: #444; }
        .entry { margin-bottom: 10px; page-break-inside: avoid; }
        .meta { color: #555; margin-bottom: 3px; }
        .meta .date { float: right; }
        .stack { color: #555; font-style: italic; }
        .skills td { vertical-align: top; padding: 1px 8px 1px 0; }
        .skills td.cat { font-weight: bold; white-space: nowrap; }
        @media screen { body { max-width: 820px; margin: 32px auto; padding: 0 16px; font-size: 13px; } }
    </style>
</head>
<body>
    <header>
        <h1>{{ $name }}</h1>
        <div class="headline">{{ $g('occupation') }}</div>
        <div class="contact">
            {{ $g('address') }}
            @if ($g('email')) · <a href="mailto:{{ $g('email') }}">{{ $g('email') }}</a> @endif
            @if ($g('phone')) · {{ $g('phone') }} @endif
            <br>
            <a href="{{ url('/') }}">{{ preg_replace('#^https?://#', '', url('/')) }}</a>
            · <a href="{{ $g('github', 'https://github.com/lexisvar') }}">GitHub</a>
            · <a href="{{ $g('linkedin', 'https://www.linkedin.com/in/lexisvar') }}">LinkedIn</a>
            @if ($g('work_rights')) <br>{{ $g('work_rights') }} @endif
        </div>
    </header>

    @if ($g('biography'))
        <h2>Summary</h2>
        <x-rich-text :text="$g('biography')" />
    @endif

    <h2>Experience</h2>
    @foreach ($experiences as $experience)
        <div class="entry">
            <div class="meta">
                <span class="date">{{ $period($experience->start_date, $experience->finish_date) }}</span>
                <h3>{{ $experience->occupation }} — {{ $experience->company }}</h3>
            </div>
            <x-rich-text :text="$experience->description" />
        </div>
    @endforeach

    @if ($projects->isNotEmpty())
        <h2>Projects</h2>
        @foreach ($projects as $project)
            <div class="entry">
                <div class="meta">
                    @if ($project->start_date)
                        <span class="date">{{ $period($project->start_date, $project->finish_date) }}</span>
                    @endif
                    <h3>{{ $project->name }}@if ($project->role) — {{ $project->role }}@endif</h3>
                </div>
                <x-rich-text :text="$project->description" />
                @if ($project->stackList())
                    <p class="stack">{{ implode(', ', $project->stackList()) }}</p>
                @endif
                @if ($project->url || $project->repo_url)
                    <p>
                        @if ($project->url)<a href="{{ $project->url }}">{{ preg_replace('#^https?://#', '', $project->url) }}</a>@endif
                        @if ($project->url && $project->repo_url) · @endif
                        @if ($project->repo_url)<a href="{{ $project->repo_url }}">{{ preg_replace('#^https?://#', '', $project->repo_url) }}</a>@endif
                    </p>
                @endif
            </div>
        @endforeach
    @endif

    @if ($skillGroups->isNotEmpty())
        <h2>Technical Skills</h2>
        <table class="skills">
            @foreach ($skillGroups as $category => $skills)
                <tr>
                    <td class="cat">{{ $category }}</td>
                    <td>{{ $skills->pluck('name')->implode(', ') }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <h2>Education</h2>
    @foreach ($educations as $education)
        <div class="entry">
            <div class="meta">
                <span class="date">{{ $period($education->start_date, $education->finish_date) }}</span>
                <h3>{{ $education->study_name }}@if ($education->study_title) ({{ $education->study_title }})@endif</h3>
            </div>
            <p>{{ $education->college }}</p>
            @if ($g('pdf_education_descriptions', '0') === '1')
                <x-rich-text :text="$education->description" />
            @endif
        </div>
    @endforeach
</body>
</html>
