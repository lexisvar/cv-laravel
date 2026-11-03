@php
    $g = fn (string $key, string $default = '') => $generals[$key] ?? $default;
    $name = $g('name', 'Alexis Vargas');
    $headline = $g('occupation', 'Software Engineer');
    $metaDescription = $g('meta_description', \Illuminate\Support\Str::limit(preg_replace('/\s+/', ' ', strip_tags($g('biography'))), 155));
    $github = $g('github', 'https://github.com/lexisvar');
    $linkedin = $g('linkedin', 'https://www.linkedin.com/in/lexisvar');
    $period = fn ($start, $finish) => ($start ? date('M Y', strtotime($start)) : '')
        .($start ? ' – ' : '')
        .($finish ? date('M Y', strtotime($finish)) : 'Present');
    $compactBefore = (int) $g('compact_before', '0');
    $isCompact = fn ($finish) => $compactBefore && $finish && (int) date('Y', strtotime($finish)) < $compactBefore;
    // "highlights": one per line, "value | label", e.g. "10+ years | building web software"
    $highlights = collect(preg_split('/\R/', $g('highlights')))
        ->map(fn ($line) => array_map('trim', explode('|', $line, 2)))
        ->filter(fn ($parts) => count($parts) === 2 && $parts[0] !== '');
    $icon = [
        'mail' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>',
        'download' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M12 3v12m0 0-4-4m4 4 4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg>',
        'github' => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 .5a11.5 11.5 0 0 0-3.64 22.41c.58.1.79-.25.79-.56v-2c-3.2.7-3.88-1.37-3.88-1.37-.53-1.33-1.28-1.69-1.28-1.69-1.05-.71.08-.7.08-.7 1.16.08 1.77 1.2 1.77 1.2 1.03 1.77 2.71 1.26 3.37.96.1-.75.4-1.26.73-1.55-2.55-.29-5.24-1.28-5.24-5.68 0-1.26.45-2.28 1.19-3.09-.12-.29-.52-1.46.11-3.05 0 0 .97-.31 3.17 1.18a11 11 0 0 1 5.77 0c2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.23 2.76.11 3.05.74.81 1.19 1.83 1.19 3.09 0 4.41-2.69 5.38-5.25 5.67.41.36.78 1.06.78 2.14v3.17c0 .31.21.67.8.56A11.5 11.5 0 0 0 12 .5Z"/></svg>',
        'linkedin' => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28ZM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12ZM7.12 20.45H3.56V9h3.56v11.45ZM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0Z"/></svg>',
    ];
@endphp
<!DOCTYPE html>
<html lang="en-AU">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $name }} · {{ $headline }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="color-scheme" content="light dark">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:type" content="profile">
    <meta property="og:title" content="{{ $name }} · {{ $headline }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('images/alexisvargas-og.jpg') }}">
    <meta property="og:image:width" content="800">
    <meta property="og:image:height" content="800">
    <meta name="twitter:card" content="summary">
    @include('partials.favicon')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}?v=3" rel="stylesheet">
    <script type="application/ld+json">
      {!! json_encode([
          '@context' => 'https://schema.org',
          '@type' => 'Person',
          'name' => $name,
          'jobTitle' => $headline,
          'url' => url('/'),
          'sameAs' => [$github, $linkedin],
          'address' => ['@type' => 'PostalAddress', 'addressLocality' => $g('address', 'Sydney, Australia')],
          'knowsAbout' => $skillGroups->flatten()->pluck('name')->values(),
      ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>
</head>
<body>
    <nav class="topnav" aria-label="Sections">
        <div class="container">
            <a class="brand" href="#top">{{ $name }}</a>
            <ul>
                <li><a href="#about">About</a></li>
                @if ($projects->isNotEmpty())<li><a href="#projects">Projects</a></li>@endif
                <li><a href="#experience">Experience</a></li>
                @if ($skillGroups->isNotEmpty())<li><a href="#skills">Skills</a></li>@endif
                <li><a href="#education">Education</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <main id="top">
        <header class="hero">
            <div class="container">
                <div class="hero-inner">
                    @if ($g('show_photo', '1') === '1')
                        <img class="avatar" src="{{ asset('images/alexisvargas.jpeg') }}?v=3" alt="Portrait of {{ $name }}" width="112" height="112">
                    @endif
                    <div>
                        <h1>{{ $name }}</h1>
                        <p class="headline">{{ $headline }}</p>
                        <ul class="meta">
                            @if ($g('address'))<li>{{ $g('address') }}</li>@endif
                            @if ($g('language'))<li>{{ $g('language') }}</li>@endif
                        </ul>
                        @if ($g('availability'))
                            <div class="badge-available">{{ $g('availability') }}</div>
                        @endif
                        <div class="actions">
                            @if ($g('email'))
                                <a class="btn btn-primary" href="mailto:{{ $g('email') }}">{!! $icon['mail'] !!} Email me</a>
                            @endif
                            <a class="btn" href="{{ url('/export') }}">{!! $icon['download'] !!} Download CV</a>
                            <a class="btn" href="{{ $github }}" rel="noopener">{!! $icon['github'] !!} GitHub</a>
                            <a class="btn" href="{{ $linkedin }}" rel="noopener">{!! $icon['linkedin'] !!} LinkedIn</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section class="block" id="about">
            <div class="container">
                <h2>About</h2>
                <x-rich-text class="lead" :text="$g('biography')" />
                @if ($highlights->isNotEmpty())
                    <ul class="facts" aria-label="Highlights">
                        @foreach ($highlights as [$value, $label])
                            <li><strong>{{ $value }}</strong><span>{{ $label }}</span></li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>

        @if ($projects->isNotEmpty())
            <section class="block" id="projects">
                <div class="container">
                    <h2>Projects</h2>
                    <div class="projects">
                        @foreach ($projects as $project)
                            <article class="project {{ $loop->first ? 'featured' : '' }}">
                                <header>
                                    <h3>{{ $project->name }}</h3>
                                    @if ($project->start_date)
                                        <span class="when">{{ $period($project->start_date, $project->finish_date) }}</span>
                                    @endif
                                </header>
                                @if ($project->role)<p class="role">{{ $project->role }}</p>@endif
                                <x-rich-text :text="$project->description" />
                                @if ($project->stackList())
                                    <ul class="chips" aria-label="Tech stack">
                                        @foreach ($project->stackList() as $tech)<li>{{ $tech }}</li>@endforeach
                                    </ul>
                                @endif
                                @if ($project->url || $project->repo_url || $project->linkList())
                                    <div class="links">
                                        @if ($project->url)<a href="{{ $project->url }}" rel="noopener" target="_blank">Visit site ↗</a>@endif
                                        @foreach ($project->linkList() as $link)<a href="{{ $link['url'] }}" rel="noopener" target="_blank">{{ $link['label'] }} ↗</a>@endforeach
                                        @if ($project->repo_url)<a href="{{ $project->repo_url }}" rel="noopener" target="_blank">Source code ↗</a>@endif
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="block" id="experience">
            <div class="container">
                <h2>Experience</h2>
                @foreach ($experiences as $experience)
                    <article class="entry {{ $isCompact($experience->finish_date) ? 'compact' : '' }}">
                        <div class="when">{{ $period($experience->start_date, $experience->finish_date) }}</div>
                        <div>
                            <h3>{{ $experience->occupation }}</h3>
                            <p class="org">{{ $experience->company }}</p>
                            @unless ($isCompact($experience->finish_date))
                                <x-rich-text :text="$experience->description" />
                            @endunless
                        </div>
                    </article>
                @endforeach
            </div>
        </section>


        @if ($skillGroups->isNotEmpty())
            <section class="block" id="skills">
                <div class="container">
                    <h2>Skills</h2>
                    <dl class="skills">
                        @foreach ($skillGroups as $category => $skills)
                            <div>
                                <dt>{{ $category }}</dt>
                                <dd>
                                    <ul class="chips">
                                        @foreach ($skills as $skill)<li>{{ $skill->name }}</li>@endforeach
                                    </ul>
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </section>
        @endif

        <section class="block" id="education">
            <div class="container">
                <h2>Education</h2>
                @foreach ($educations as $education)
                    <article class="entry">
                        <div class="when">{{ $period($education->start_date, $education->finish_date) }}</div>
                        <div>
                            <h3>{{ $education->study_name }}</h3>
                            <p class="org">{{ $education->college }}@if ($education->study_title) · {{ $education->study_title }}@endif</p>
                            <x-rich-text :text="$education->description" />
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="block" id="contact">
            <div class="container">
                <h2>Contact</h2>
                <div class="contact-card">
                    <div>
                        <h3>{{ $g('contact_title', "Let's talk") }}</h3>
                        <p>{{ $g('contact_text', 'I am open to backend and full-stack engineering roles in Sydney. The quickest way to reach me is by email.') }}</p>
                    </div>
                    <div class="actions">
                        @if ($g('email'))
                            <a class="btn btn-primary" href="mailto:{{ $g('email') }}">{!! $icon['mail'] !!} {{ $g('email') }}</a>
                        @endif
                        @if ($g('phone') && $g('show_phone', '1') === '1')
                            <a class="btn" href="tel:{{ preg_replace('/[^+\d]/', '', $g('phone')) }}">{{ $g('phone') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="site">
        <div class="container">
            <span>&copy; {{ date('Y') }} {{ $name }}</span>
            <span><a href="{{ url('/simple') }}">Plain-text CV</a> · <a href="{{ url('/export') }}">PDF</a></span>
        </div>
    </footer>
</body>
</html>
