<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Refocus the CV on Shore and on Caissify's infrastructure, with less
 * weight on the pairing engine: new About, PDF summary and highlights,
 * an infrastructure-led Caissify description (version 2.0 out on Google
 * Play first) and a one-line caissify-pairings entry. Each field changes
 * only while it still holds the previous text, so admin edits are kept.
 */
return new class extends Migration
{
    private const PREVIOUS = array (
  'generals' => 
  array (
    'biography' => 'I build web, mobile and backend software end to end. Since 2025 I have designed, built and run Caissify on my own: a Django API with real-time features, a Vue web app, Flutter apps on the App Store and Google Play, and a Rust desktop app.

Before that, I spent ten years building production web applications and APIs in PHP/Laravel and Ruby on Rails.

I work with AI coding agents (Claude Code, Cursor) every day, with written rules, CI and code review, so I move fast without losing quality.

Chess is where Caissify started: I am a chess player and coach, which gave me the domain knowledge behind its open-source FIDE pairing engine.',
    'pdf_summary' => 'Software engineer who builds web, mobile and backend systems end to end. Since 2025 I have designed, built and run Caissify on my own: a Django/DRF API with Celery and WebSockets, a Vue 3/TypeScript web app, Flutter apps on the App Store and Google Play, and a Rust/Tauri desktop app. Its open-source FIDE pairing engine (caissify-pairings on PyPI) showed 0 discrepancies against bbpPairings across 10,000 simulated tournaments. Before that, ten years building production web applications and APIs in PHP/Laravel and Ruby on Rails. I work with Claude Code and Cursor every day, with written rules, CI and code review. Chess player and coach.',
    'highlights' => '10+ years | building production web software
4 platforms | web, iOS, Android and desktop, built solo
10,000 tournaments | pairing engine checked against FIDE\'s reference engine',
  ),
  'caissify' => 'Chess platform for playing, learning and coaching that I designed and built as the only engineer: about 2,000 commits across 12 repositories since June 2025. Version 2.0 is live on Google Play and the App Store, in English and Spanish.
- Real-time play on Django Channels: a lobby with instant pairing, live clocks, premoves, automatic reconnection, spectator chat, and Rush tournaments with live standings
- Live coaching: coaches drive a shared board in real time, set homework and manage student groups, with child-safety features built in (kid mode, parental consent)
- Django 5.2 / DRF API with about 330 API operations and about 1,050 tests, running on Railway as four services (web, Celery worker, Celery beat, WebSockets); room state in Redis with a PostgreSQL event log
- Flutter app for iOS and Android with 545 automated tests in CI, and a Vue 3 + TypeScript web app with Stockfish compiled to WebAssembly
- Offline-first Rust/Tauri desktop app for tournament arbiters that syncs through idempotency keys; own Glicko-2 rating implementation',
  'pairings_name' => 'caissify-pairings: open-source FIDE pairing engine',
  'pairings' => 'Pure-Python library for chess tournament pairings, published on PyPI (12 releases).
- Implements the FIDE Dutch Swiss system with Baku acceleration, FIDE Berger round-robin and a casual Swiss mode, plus TRF16 import and export.
- Pairs each round with maximum-weight matching (Edmonds\' blossom algorithm); the edge weights encode the FIDE criteria.
- 0 discrepancies against bbpPairings, FIDE\'s reference engine, across 10,000 simulated tournaments. Not FIDE-endorsed.
- 252 tests, run in CI on Linux and macOS with Python 3.10 to 3.13.',
);

    public function up()
    {
        $content = require database_path('content/cv.php');
        $project = fn ($name) => collect($content['projects'])->firstWhere('name', $name);

        foreach (self::PREVIOUS['generals'] as $key => $previous) {
            DB::table('generals')->where('key', $key)->where('value', $previous)
                ->update(['value' => $content['generals'][$key], 'updated_at' => now()]);
        }

        DB::table('projects')->where('name', 'Caissify')->where('description', self::PREVIOUS['caissify'])
            ->update(['description' => $project('Caissify')['description'], 'updated_at' => now()]);

        DB::table('projects')->where('name', self::PREVIOUS['pairings_name'])->where('description', self::PREVIOUS['pairings'])
            ->update([
                'name' => 'caissify-pairings',
                'description' => $project('caissify-pairings')['description'],
                'updated_at' => now(),
            ]);
    }

    public function down()
    {
        // Content changes are not reverted automatically.
    }
};
