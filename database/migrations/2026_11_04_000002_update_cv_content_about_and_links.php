<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * One-time content update for sites that already loaded the initial CV:
 * new About text (leads with current work, adds chess and AI), new PDF
 * summary and highlights, and App Store / Google Play links for Caissify.
 *
 * A field is replaced only if it still holds the original text, so
 * anything edited in the admin is left alone. Empty databases are skipped
 * (app:load-cv-content fills them with the new content).
 */
return new class extends Migration
{
    private const ORIGINAL = array (
  'biography' => 'I am a software engineer with about ten years of commercial experience (2012–2023) building web applications and APIs in PHP/Laravel and Ruby on Rails, with PostgreSQL, Elasticsearch and Vue.js.

Since June 2025 I have designed and built Caissify as its only engineer: a chess learning and tournament platform with a Django REST API, a Vue 3 web app, Flutter apps on the App Store and Google Play, and an offline-first desktop app for tournament arbiters. Part of this work is caissify-pairings, an open-source FIDE pairing engine published on PyPI.

I am looking for a backend or full-stack role in a product team where I can contribute quickly.
- Backend: Python, Django, Django REST Framework, Celery, Django Channels, PostgreSQL, Redis
- Frontend and mobile: Vue 3, TypeScript, React, Flutter
- How I work: CI on every repository, staging before production, written guidelines for AI coding agents, and I review and own every change',
  'pdf_summary' => 'Backend-leaning full-stack engineer with about ten years of commercial PHP/Laravel and Ruby on Rails experience (2012–2023). Since June 2025 I have built and shipped Caissify as its only engineer: a Django/DRF API with Celery and WebSockets, a Vue 3/TypeScript web app, Flutter apps on the App Store and Google Play, and a Rust/Tauri desktop app. I wrote and published caissify-pairings on PyPI, a FIDE Dutch Swiss pairing engine that showed 0 discrepancies against bbpPairings across 10,000 simulated tournaments. I use Claude Code and Cursor daily, with written agent rules, CI, staging-first releases and my own review of every change.',
  'highlights' => '10 years | commercial web development, 2012–2023
4 platforms | web, iOS, Android and desktop, built solo
10,000 tournaments | pairing engine checked against FIDE\'s reference engine',
);

    public function up()
    {
        $content = require database_path('content/cv.php');

        foreach (self::ORIGINAL as $key => $original) {
            DB::table('generals')
                ->where('key', $key)
                ->where('value', $original)
                ->update(['value' => $content['generals'][$key], 'updated_at' => now()]);
        }

        $caissify = collect($content['projects'])->firstWhere('name', 'Caissify');
        DB::table('projects')
            ->where('name', 'Caissify')
            ->where(function ($query) {
                $query->whereNull('links')->orWhere('links', '');
            })
            ->update(['links' => $caissify['links'], 'updated_at' => now()]);
    }

    public function down()
    {
        // Content changes are not reverted automatically.
    }
};
