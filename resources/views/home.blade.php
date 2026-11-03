@php
    $g = fn (string $key, string $default = '') => $generals[$key] ?? $default;
    $name = $g('name', 'Alexis Vargas');
    $headline = $g('occupation', 'Software Engineer');
    $metaDescription = $g('meta_description', \Illuminate\Support\Str::limit(strip_tags($g('biography')), 155));
    $github = $g('github', 'https://github.com/lexisvar');
    $linkedin = $g('linkedin', 'https://www.linkedin.com/in/lexisvar');
    $period = fn ($start, $finish) => ($start ? date('M Y', strtotime($start)) : '')
        .($start ? ' – ' : '')
        .($finish ? date('M Y', strtotime($finish)) : 'Present');
@endphp
<!DOCTYPE html>
<html lang="en-AU">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $name }} · {{ $headline }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:type" content="profile">
    <meta property="og:title" content="{{ $name }} · {{ $headline }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('images/alexisvargas.jpeg') }}">
    <meta name="twitter:card" content="summary">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/aos.css?ver=1.1.0" rel="stylesheet">
    <link href="css/bootstrap.min.css?ver=1.1.0" rel="stylesheet">
    <link href="css/main.css?ver=1.1.0" rel="stylesheet">
    <link href="css/custom.css?ver=1.0.0" rel="stylesheet">
    <noscript>
      <style type="text/css">
        [data-aos] { opacity: 1 !important; transform: translate(0) scale(1) !important; }
      </style>
    </noscript>
    <script type="application/ld+json">
      {!! json_encode([
          '@context' => 'https://schema.org',
          '@type' => 'Person',
          'name' => $name,
          'jobTitle' => $headline,
          'url' => url('/'),
          'sameAs' => [$github, $linkedin],
          'address' => ['@type' => 'PostalAddress', 'addressLocality' => $g('address', 'Sydney, Australia')],
      ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>
  </head>
  <body id="top">
    <header>
      <div class="profile-page sidebar-collapse">
        <nav class="navbar navbar-expand-lg fixed-top navbar-transparent bg-primary" color-on-scroll="400">
          <div class="container">
            <div class="navbar-translate"><a class="navbar-brand" href="#top">{{ $name }}</a>
              <button class="navbar-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-bar bar1"></span><span class="navbar-toggler-bar bar2"></span><span class="navbar-toggler-bar bar3"></span></button>
            </div>
            <div class="collapse navbar-collapse justify-content-end" id="navigation">
              <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link smooth-scroll" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link smooth-scroll" href="#experience">Experience</a></li>
                @if ($projects->isNotEmpty())
                  <li class="nav-item"><a class="nav-link smooth-scroll" href="#projects">Projects</a></li>
                @endif
                <li class="nav-item"><a class="nav-link smooth-scroll" href="#skill">Skills</a></li>
                <li class="nav-item"><a class="nav-link smooth-scroll" href="#education">Education</a></li>
                <li class="nav-item"><a class="nav-link smooth-scroll" href="#contact">Contact</a></li>
              </ul>
            </div>
          </div>
        </nav>
      </div>
    </header>

    <main class="page-content">
      <div class="profile-page">
        <div class="wrapper">
          <div class="page-header page-header-small" filter-color="green">
            <div class="page-header-image" data-parallax="true" style="background-image: url('images/bgroundd.jpg')"></div>
            <div class="container">
              <div class="content-center">
                <div class="cc-profile-image"><img src="images/alexisvargas.jpeg" alt="Photo of {{ $name }}"/></div>
                <h1 class="h2 title">{{ $name }}</h1>
                <p class="category text-white">{{ $headline }}</p>
                @if ($g('availability'))
                  <p class="cc-availability text-white">{{ $g('availability') }}</p>
                @endif
                <a class="btn btn-primary smooth-scroll mr-2" href="#contact">Get in touch</a>
                <a class="btn btn-primary" href="/export">Download CV (PDF)</a>
              </div>
            </div>
            <div class="section">
              <div class="container">
                <div class="button-container">
                  <a class="btn btn-default btn-round btn-lg btn-icon" href="{{ $github }}" title="GitHub" aria-label="GitHub"><i class="fa fa-github"></i></a>
                  <a class="btn btn-default btn-round btn-lg btn-icon" href="{{ $linkedin }}" title="LinkedIn" aria-label="LinkedIn"><i class="fa fa-linkedin-square"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <section class="section" id="about">
        <div class="container">
          <div class="card" data-aos="fade-up" data-aos-offset="10">
            <div class="row">
              <div class="col-lg-7 col-md-12">
                <div class="card-body">
                  <h2 class="h4 mt-0 title">About</h2>
                  <x-rich-text :text="$g('biography')" />
                </div>
              </div>
              <div class="col-lg-5 col-md-12">
                <div class="card-body">
                  <h2 class="h4 mt-0 title">Basic Information</h2>
                  @foreach (['email' => 'Email', 'address' => 'Location', 'language' => 'Languages', 'work_rights' => 'Work rights'] as $key => $label)
                    @if ($g($key))
                      <div class="row mt-3">
                        <div class="col-sm-4"><strong class="text-uppercase">{{ $label }}:</strong></div>
                        <div class="col-sm-8">
                          @if ($key === 'email')
                            <a href="mailto:{{ $g('email') }}">{{ $g('email') }}</a>
                          @else
                            {{ $g($key) }}
                          @endif
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="section" id="experience">
        <div class="container cc-experience">
          <h2 class="h4 text-center mb-4 title">Work Experience</h2>
          @foreach ($experiences as $experience)
            <article class="card">
              <div class="row">
                <div class="col-md-3 bg-primary" data-aos="fade-right" data-aos-offset="50" data-aos-duration="500">
                  <div class="card-body cc-experience-header">
                    <p>{{ $period($experience->start_date, $experience->finish_date) }}</p>
                    <div class="h5">{{ $experience->company }}</div>
                  </div>
                </div>
                <div class="col-md-9" data-aos="fade-left" data-aos-offset="50" data-aos-duration="500">
                  <div class="card-body">
                    <h3 class="h5">{{ $experience->occupation }}</h3>
                    <x-rich-text :text="$experience->description" />
                  </div>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      </section>

      @if ($projects->isNotEmpty())
        <section class="section" id="projects">
          <div class="container">
            <h2 class="h4 text-center mb-4 title">Projects</h2>
            @foreach ($projects as $project)
              <article class="card cc-project" data-aos="fade-up" data-aos-offset="10">
                <div class="card-body">
                  <div class="d-flex flex-wrap justify-content-between align-items-baseline">
                    <h3 class="h5 mb-1">{{ $project->name }}</h3>
                    @if ($project->start_date)
                      <span class="text-muted small">{{ $period($project->start_date, $project->finish_date) }}</span>
                    @endif
                  </div>
                  @if ($project->role)
                    <p class="category mb-2">{{ $project->role }}</p>
                  @endif
                  <x-rich-text :text="$project->description" />
                  @if ($project->stackList())
                    <ul class="cc-chips" aria-label="Tech stack">
                      @foreach ($project->stackList() as $tech)
                        <li>{{ $tech }}</li>
                      @endforeach
                    </ul>
                  @endif
                  @if ($project->url || $project->repo_url)
                    <p class="mt-2 mb-0">
                      @if ($project->url)<a href="{{ $project->url }}" rel="noopener" target="_blank">Live site</a>@endif
                      @if ($project->url && $project->repo_url) · @endif
                      @if ($project->repo_url)<a href="{{ $project->repo_url }}" rel="noopener" target="_blank">Source</a>@endif
                    </p>
                  @endif
                </div>
              </article>
            @endforeach
          </div>
        </section>
      @endif

      <section class="section" id="skill">
        <div class="container">
          <h2 class="h4 text-center mb-4 title">Technical Skills</h2>
          <div class="card" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
            <div class="card-body">
              <div class="row">
                @foreach ($skillGroups as $category => $skills)
                  <div class="col-md-6 mb-3">
                    <h3 class="h6 text-uppercase mb-2">{{ $category }}</h3>
                    <ul class="cc-chips">
                      @foreach ($skills as $skill)
                        <li>{{ $skill->name }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="section" id="education">
        <div class="container cc-education">
          <h2 class="h4 text-center mb-4 title">Education</h2>
          @foreach ($educations as $education)
            <article class="card">
              <div class="row">
                <div class="col-md-3 bg-primary" data-aos="fade-right" data-aos-offset="50" data-aos-duration="500">
                  <div class="card-body cc-education-header">
                    <p>{{ $period($education->start_date, $education->finish_date) }}</p>
                    <div class="h5">{{ $education->study_title }}</div>
                  </div>
                </div>
                <div class="col-md-9" data-aos="fade-left" data-aos-offset="50" data-aos-duration="500">
                  <div class="card-body">
                    <h3 class="h5">{{ $education->study_name }}</h3>
                    <p class="category">{{ $education->college }}</p>
                    <x-rich-text :text="$education->description" />
                  </div>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      </section>

      <section class="section" id="contact">
        <div class="cc-contact-information" style="background-image: url('images/staticmap.png')">
          <div class="container">
            <div class="cc-contact">
              <div class="row">
                <div class="col-md-9">
                  <div class="card mb-0" data-aos="zoom-in">
                    <h2 class="h4 text-center title">Contact</h2>
                    <div class="card-body">
                      @if ($g('address'))
                        <p class="mb-0"><strong>Location</strong></p>
                        <p class="pb-2">{{ $g('address') }}</p>
                      @endif
                      @if ($g('phone') && $g('show_phone', '1') === '1')
                        <p class="mb-0"><strong>Phone</strong></p>
                        <p class="pb-2"><a href="tel:{{ preg_replace('/[^+\d]/', '', $g('phone')) }}">{{ $g('phone') }}</a></p>
                      @endif
                      <p class="mb-0"><strong>Email</strong></p>
                      <p><a href="mailto:{{ $g('email') }}">{{ $g('email') }}</a></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="footer">
      <div class="container text-center">
        <a class="cc-github btn btn-link" href="{{ $github }}" aria-label="GitHub"><i class="fa fa-github fa-2x" aria-hidden="true"></i></a>
        <a class="cc-linkedin btn btn-link" href="{{ $linkedin }}" aria-label="LinkedIn"><i class="fa fa-linkedin fa-2x" aria-hidden="true"></i></a>
      </div>
      <div class="h4 title text-center">{{ $name }}</div>
      <div class="text-center text-muted">
        <p>&copy; {{ date('Y') }} {{ $name }}</p>
      </div>
    </footer>
    <script src="js/core/jquery.3.2.1.min.js?ver=1.1.0"></script>
    <script src="js/core/popper.min.js?ver=1.1.0"></script>
    <script src="js/core/bootstrap.min.js?ver=1.1.0"></script>
    <script src="js/now-ui-kit.js?ver=1.1.0"></script>
    <script src="js/aos.js?ver=1.1.0"></script>
    <script src="scripts/main.js?ver=1.1.0"></script>
  </body>
</html>
