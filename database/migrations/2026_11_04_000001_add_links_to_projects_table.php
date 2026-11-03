<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Extra links per project, one per line: "Label | https://...".
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('links')->nullable()->after('repo_url');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('links');
        });
    }
};
