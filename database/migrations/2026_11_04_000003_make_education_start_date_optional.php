<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Education start dates become optional: when empty, only the completion
 * date is shown. Also removes the start year from the degree loaded by the
 * initial content (only if it still has the original value).
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('educations', function (Blueprint $table) {
            $table->date('start_date')->nullable()->change();
        });

        DB::table('educations')
            ->where('study_name', 'Bachelor of Systems Engineering')
            ->where('start_date', '2009-08-01')
            ->update(['start_date' => null, 'updated_at' => now()]);

        DB::table('educations')
            ->where('study_name', 'Bachelor of Systems Engineering')
            ->where('description', 'Degree completed while working in industry. Conferred 30 October 2026.')
            ->update(['description' => '', 'updated_at' => now()]);
    }

    public function down()
    {
        // Not reverted: existing rows may now have no start date.
    }
};
