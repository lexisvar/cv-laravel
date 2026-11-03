<?php

/*
|--------------------------------------------------------------------------
| Initial CV content
|--------------------------------------------------------------------------
| Loaded once by `php artisan app:load-cv-content` when the CV is empty.
| After that, edit everything in the admin; this file is not re-applied.
|
| Descriptions: blank lines separate paragraphs, lines starting with "- "
| become bullet points.
*/

return [

    'generals' => [
        'name' => 'Alexis Vargas',
        'occupation' => 'Software Engineer · Backend & Full-Stack (Python/Django, Vue, Flutter)',
        'address' => 'Sydney, NSW, Australia',
        'language' => 'English (professional), Spanish (native)',
        'email' => 'lexisvar@gmail.com',
        'github' => 'https://github.com/lexisvar',
        'linkedin' => 'https://www.linkedin.com/in/lexisvar',
        'show_phone' => '0',
        'availability' => 'Open to backend and full-stack roles in Sydney',
        'compact_before' => '2018',
        'pdf_project_limit' => '3',
        'skill_categories' => 'Backend, Frontend, Mobile & Desktop, Data, Cloud & DevOps, AI-assisted development, Also experienced in',
        'meta_description' => 'Software engineer in Sydney. Python/Django APIs, Vue/TypeScript, Flutter apps and real-time systems, plus 10 years of commercial PHP/Laravel and Ruby on Rails work.',

        'biography' => <<<'TEXT'
I build web, mobile and backend software end to end. Since 2025 I have designed, built and run Caissify on my own: a Django API with real-time features, a Vue web app, Flutter apps on the App Store and Google Play, and a Rust desktop app.

Before that, I spent ten years building production web applications and APIs in PHP/Laravel and Ruby on Rails.

I work with AI coding agents (Claude Code, Cursor) every day, with written rules, CI and code review, so I move fast without losing quality.

Chess is where Caissify started: I am a chess player and coach, which gave me the domain knowledge behind its open-source FIDE pairing engine.
TEXT,

        'pdf_summary' => <<<'TEXT'
Software engineer who builds web, mobile and backend systems end to end. Since 2025 I have designed, built and run Caissify on my own: a Django/DRF API with Celery and WebSockets, a Vue 3/TypeScript web app, Flutter apps on the App Store and Google Play, and a Rust/Tauri desktop app. Its open-source FIDE pairing engine (caissify-pairings on PyPI) showed 0 discrepancies against bbpPairings across 10,000 simulated tournaments. Before that, ten years building production web applications and APIs in PHP/Laravel and Ruby on Rails. I work with Claude Code and Cursor every day, with written rules, CI and code review. Chess player and coach.
TEXT,

        'highlights' => <<<'TEXT'
10+ years | building production web software
4 platforms | web, iOS, Android and desktop, built solo
10,000 tournaments | pairing engine checked against FIDE's reference engine
TEXT,

        'contact_title' => "Let's talk",
        'contact_text' => 'I am open to backend and full-stack engineering roles in Sydney. I am happy to walk you through the Caissify code or the pairing engine. The quickest way to reach me is by email.',
    ],

    'experiences' => [
        [
            'occupation' => 'Chess Coach',
            'company' => 'Sydney Academy of Chess',
            'start_date' => '2025-05-22',
            'finish_date' => null,
            'description' => <<<'TEXT'
- Teach group classes, private lessons and training squads for juniors and adults
- Plan lessons and progressions, and prepare students for rated tournaments
- This work shaped the tournament and coaching tools I built in Caissify
TEXT,
        ],
        [
            'occupation' => 'Ruby on Rails Developer',
            'company' => 'Shore',
            'start_date' => '2022-02-01',
            'finish_date' => '2023-03-01',
            'description' => <<<'TEXT'
- Developed and maintained features in a Ruby on Rails SaaS platform
- Wrote complex PostgreSQL queries and reports, and connected them to Vue.js front ends
- Implemented Elasticsearch-backed search
- Worked in sprints with Jira, pull-request code review and CI in an Agile team
TEXT,
        ],
        [
            'occupation' => 'PHP Laravel Developer',
            'company' => 'Grupo Bien Pensado',
            'start_date' => '2020-01-01',
            'finish_date' => '2022-02-01',
            'description' => <<<'TEXT'
- Designed and built REST APIs in Laravel for several client projects, used by web and mobile apps
- Modelled relational schemas and wrote efficient queries
- Agreed API contracts with front-end and mobile developers
TEXT,
        ],
        [
            'occupation' => 'PHP Analyst',
            'company' => 'HandyCommerce',
            'start_date' => '2017-06-01',
            'finish_date' => '2019-03-01',
            'description' => <<<'TEXT'
- Maintained and extended e-commerce products built on Laravel, WordPress/WooCommerce, Vue.js and Angular
- Integrated Elasticsearch for product search
- Delivered client features in a small development team
TEXT,
        ],
        [
            'occupation' => 'Project Manager & Developer',
            'company' => 'Solvitio',
            'start_date' => '2015-01-01',
            'finish_date' => '2017-11-01',
            'description' => <<<'TEXT'
- Led a small team that built POSNube, a cloud point-of-sale product, and client websites
- Gathered requirements with clients, estimated and planned work, and assigned tasks
- Developed in PHP, Laravel, CodeIgniter and MySQL
TEXT,
        ],
        [
            'occupation' => 'Linux Server Support',
            'company' => 'Factoria Digital',
            'start_date' => '2013-03-01',
            'finish_date' => '2014-11-01',
            'description' => '- Administered Linux hosting: DNS, domains, deployments and malware clean-up for CMS sites',
        ],
        [
            'occupation' => 'PHP Developer',
            'company' => 'Grupo i33',
            'start_date' => '2012-03-01',
            'finish_date' => '2013-03-01',
            'description' => '- Built client websites in PHP/CodeIgniter',
        ],
    ],

    'projects' => [
        [
            'name' => 'Caissify',
            'role' => 'Founder & Lead Engineer (self-directed)',
            'url' => 'https://caissify.com',
            'repo_url' => null,
            'links' => "App Store | https://apps.apple.com/us/app/caissify/id6755508097\nGoogle Play | https://play.google.com/store/apps/details?id=com.caissify.chess",
            'stack' => 'Python, Django, Django REST Framework, Celery, Django Channels, PostgreSQL, Redis, Vue 3, TypeScript, Flutter, Rust, Tauri, Docker, Railway, GitHub Actions',
            'start_date' => '2025-06-01',
            'finish_date' => null,
            'sort_order' => 1,
            'description' => <<<'TEXT'
Chess learning and tournament platform that I designed and built as the only engineer: about 2,000 commits across 12 repositories since June 2025.
- Django 5.2 / DRF API with about 330 API operations and about 1,050 tests, running on Railway as four services from one Docker image (web, Celery worker, Celery beat, WebSockets). CI runs the tests against PostgreSQL and Redis.
- Real-time live lessons and games on Django Channels: room state in Redis, every event stored in a PostgreSQL event log, and state rebuilt from the log after a restart.
- Flutter app for iOS and Android, live on the App Store and Google Play, in English and Spanish, with push notifications and on-device Stockfish analysis.
- Vue 3 + TypeScript web app with Stockfish compiled to WebAssembly in a Web Worker and an OpenAPI-generated API client.
- Offline-first desktop app for tournament arbiters that syncs with the API through idempotency keys, so a retried request never applies twice.
- Own Glicko-2 rating implementation for puzzles and players, checked against Glickman's published worked example.
TEXT,
        ],
        [
            'name' => 'caissify-pairings: open-source FIDE pairing engine',
            'role' => 'Author and maintainer (open source)',
            'url' => 'https://pypi.org/project/caissify-pairings/',
            'repo_url' => 'https://github.com/lexisvar/caissify_pairings',
            'stack' => 'Python, NetworkX, pytest, GitHub Actions, PyPI',
            'start_date' => '2026-04-01',
            'finish_date' => null,
            'sort_order' => 2,
            'description' => <<<'TEXT'
Pure-Python library for chess tournament pairings, published on PyPI (12 releases).
- Implements the FIDE Dutch Swiss system with Baku acceleration, FIDE Berger round-robin and a casual Swiss mode, plus TRF16 import and export.
- Pairs each round with maximum-weight matching (Edmonds' blossom algorithm); the edge weights encode the FIDE criteria.
- 0 discrepancies against bbpPairings, FIDE's reference engine, across 10,000 simulated tournaments. Not FIDE-endorsed.
- 252 tests, run in CI on Linux and macOS with Python 3.10 to 3.13.
TEXT,
        ],
        [
            'name' => 'Caissify Tournament Manager (desktop)',
            'role' => 'Founder & Lead Engineer (self-directed)',
            'url' => 'https://github.com/lexisvar/caissify_tm_releases',
            'repo_url' => null,
            'stack' => 'Rust, Tauri 2, React 19, TypeScript, SQLite, Python, GitHub Actions',
            'start_date' => '2026-04-01',
            'finish_date' => null,
            'sort_order' => 3,
            'description' => <<<'TEXT'
Offline-first desktop app for chess arbiters: Swiss and round-robin pairings, TRF16 import/export, tiebreaks, PDF sheets and one-click sync to the cloud.
- Rust backend with SQLite, React 19 + TypeScript front end, English and Spanish.
- Ships the Python pairing engine inside the app as a self-contained sidecar.
- GitHub Actions builds signed installers for macOS (notarized), Windows and Linux; CI runs type checks, tests, cargo fmt and clippy.
TEXT,
        ],
        [
            'name' => 'Pirakito: private, local-first finance app',
            'role' => 'Personal project',
            'url' => null,
            'repo_url' => null,
            'stack' => 'TypeScript, Vue 3, PWA, IndexedDB, libsodium, WebAuthn, PocketBase, Playwright',
            'start_date' => '2026-10-01',
            'finish_date' => null,
            'sort_order' => 4,
            'description' => <<<'TEXT'
End-to-end encrypted household finance app that works offline first.
- Integer-only money, an immutable ledger corrected by reversals, and an offline outbox that never drops changes.
- Passkey unlock with WebAuthn; data is stored encrypted in IndexedDB and synced through PocketBase.
- About 780 tests: unit, property-based, Playwright end-to-end and upgrade tests, all in GitHub Actions CI.
TEXT,
        ],
        [
            'name' => 'lexisvar.me',
            'role' => 'Personal project',
            'url' => 'https://lexisvar.me',
            'repo_url' => 'https://github.com/lexisvar/cv-laravel',
            'stack' => 'PHP, Laravel, Blade, dompdf, PHPUnit, Docker, Railway',
            'start_date' => null,
            'finish_date' => null,
            'sort_order' => 5,
            'description' => 'This site: a small Laravel CMS that renders my CV as a web page, an ATS-friendly page and a PDF from one data source, with an admin area, feature tests and a Docker deploy on Railway.',
        ],
    ],

    'skills' => [
        'Backend' => ['Python', 'Django', 'Django REST Framework', 'Celery', 'Django Channels / WebSockets', 'REST API design', 'OpenAPI'],
        'Frontend' => ['Vue 3', 'TypeScript', 'React', 'Pinia', 'Vite', 'Tailwind CSS'],
        'Mobile & Desktop' => ['Flutter', 'Dart', 'Rust', 'Tauri'],
        'Data' => ['PostgreSQL', 'Redis', 'SQLite', 'Elasticsearch'],
        'Cloud & DevOps' => ['Docker', 'Railway', 'GitHub Actions', 'Sentry', 'AWS S3', 'Linux'],
        'AI-assisted development' => ['Claude Code', 'Cursor', 'LLM APIs'],
        'Also experienced in' => ['PHP', 'Laravel', 'Ruby', 'Ruby on Rails', 'MySQL'],
    ],

    'educations' => [
        [
            'study_name' => 'Bachelor of Systems Engineering',
            'study_title' => 'Ingeniero de Sistemas',
            'college' => 'Corporación Universitaria Remington, Colombia',
            'start_date' => '2009-08-01',
            'finish_date' => '2026-10-30',
            'description' => 'Degree completed while working in industry. Conferred 30 October 2026.',
        ],
    ],
];
