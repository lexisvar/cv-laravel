<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * About and PDF summary: add the preference for backend and infrastructure
 * work (Linux servers, deployment, AWS, Railway) and the career start in
 * the CodeIgniter / early Laravel era. Only replaces text that is still the
 * previous version, so admin edits are kept.
 */
return new class extends Migration
{
    private const PREVIOUS = array (
  'biography' => 'I build web, mobile and backend software end to end. Since 2025 I have designed, built and run Caissify on my own: a Django API with real-time features, a Vue web app, Flutter apps on Google Play and the App Store, and a Rust desktop app, deployed on Railway with CI, staging and production.

Before that, I spent ten years building production web applications and APIs, most recently as a Solution Engineer at Shore Consulting Group in Toronto, working in Ruby on Rails, Python/Django, Vue and React across several client projects.

I work with AI coding agents (Claude Code, Cursor) every day, with written rules, CI and code review, so I move fast without losing quality.

Chess is where Caissify started: I am a chess player and coach.',
  'pdf_summary' => 'Software engineer who builds web, mobile and backend systems end to end. Most recently a Solution Engineer at Shore Consulting Group (Toronto), building client solutions in Ruby on Rails, Python/Django, Vue and React. Since 2025 I have designed, built and run Caissify on my own: a Django/DRF API with Celery and WebSockets on Railway (web, worker, scheduler and real-time services; CI, staging and production), a Vue 3/TypeScript web app, Flutter apps on Google Play and the App Store, and a Rust/Tauri desktop app. Ten years of production experience with Rails, Django and Laravel. I work with Claude Code and Cursor every day, with written rules, CI and code review.',
);

    public function up()
    {
        $content = require database_path('content/cv.php');

        foreach (self::PREVIOUS as $key => $previous) {
            DB::table('generals')->where('key', $key)->where('value', $previous)
                ->update(['value' => $content['generals'][$key], 'updated_at' => now()]);
        }
    }

    public function down()
    {
        // Content changes are not reverted automatically.
    }
};
