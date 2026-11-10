<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Contact card text without naming a specific project. Only replaces the
 * previous wording, so an edit made in the admin is kept.
 */
return new class extends Migration
{
    private const PREVIOUS = 'I am open to backend and full-stack engineering roles in Sydney. I am happy to walk you through the Caissify code or the pairing engine. The quickest way to reach me is by email.';

    public function up()
    {
        $content = require database_path('content/cv.php');

        DB::table('generals')->where('key', 'contact_text')->where('value', self::PREVIOUS)
            ->update(['value' => $content['generals']['contact_text'], 'updated_at' => now()]);
    }

    public function down()
    {
        // Content changes are not reverted automatically.
    }
};
