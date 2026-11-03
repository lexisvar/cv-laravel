<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Skills are now grouped by category (e.g. "Backend", "Frontend") and the
     * self-rated percentage becomes optional; it is no longer shown publicly.
     */
    public function up()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->string('category')->nullable()->after('name');
            $table->unsignedInteger('sort_order')->default(0)->after('category');
            $table->integer('percent')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn(['category', 'sort_order']);
        });
    }
};
