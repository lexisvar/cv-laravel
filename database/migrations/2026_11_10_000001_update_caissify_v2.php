<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Caissify 2.0: replaces the project description with the version 2.0
 * wording (live play, Rush tournaments, coaching, 545 mobile tests) while
 * it still holds the original text, so admin edits are kept.
 */
return new class extends Migration
{
    private const ORIGINAL = 'Chess learning and tournament platform that I designed and built as the only engineer: about 2,000 commits across 12 repositories since June 2025.
- Django 5.2 / DRF API with about 330 API operations and about 1,050 tests, running on Railway as four services from one Docker image (web, Celery worker, Celery beat, WebSockets). CI runs the tests against PostgreSQL and Redis.
- Real-time live lessons and games on Django Channels: room state in Redis, every event stored in a PostgreSQL event log, and state rebuilt from the log after a restart.
- Flutter app for iOS and Android, live on the App Store and Google Play, in English and Spanish, with push notifications and on-device Stockfish analysis.
- Vue 3 + TypeScript web app with Stockfish compiled to WebAssembly in a Web Worker and an OpenAPI-generated API client.
- Offline-first desktop app for tournament arbiters that syncs with the API through idempotency keys, so a retried request never applies twice.
- Own Glicko-2 rating implementation for puzzles and players, checked against Glickman\'s published worked example.';

    public function up()
    {
        $content = require database_path('content/cv.php');
        $caissify = collect($content['projects'])->firstWhere('name', 'Caissify');

        DB::table('projects')
            ->where('name', 'Caissify')
            ->where('description', self::ORIGINAL)
            ->update(['description' => $caissify['description'], 'updated_at' => now()]);
    }

    public function down()
    {
        // Content changes are not reverted automatically.
    }
};
