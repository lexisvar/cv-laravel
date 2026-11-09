<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Updates the Shore entry with the details from the Shore contract and
 * Amanda di Florio's confirmation: Shore Consulting Group (Toronto, remote,
 * contract), Solution Developer → Solution Engineer / Product Engineer,
 * 14 Feb 2022 – 31 Jan 2024. Only applied while the entry still holds the
 * original placeholder, so edits made in the admin are kept.
 */
return new class extends Migration
{
    public function up()
    {
        $content = require database_path('content/cv.php');
        $shore = collect($content['experiences'])->first(
            fn ($experience) => str_starts_with($experience['company'], 'Shore')
        );

        DB::table('experiences')
            ->where('company', 'Shore')
            ->where('occupation', 'Ruby on Rails Developer')
            ->whereDate('start_date', '2022-02-01')
            ->update([
                'occupation' => $shore['occupation'],
                'company' => $shore['company'],
                'start_date' => $shore['start_date'],
                'finish_date' => $shore['finish_date'],
                'description' => $shore['description'],
                'updated_at' => now(),
            ]);
    }

    public function down()
    {
        // Content changes are not reverted automatically.
    }
};
