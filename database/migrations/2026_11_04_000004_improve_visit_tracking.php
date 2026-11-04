<?php

use App\Support\VisitClassifier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Visit log: classify each visit (human / link preview / bot), record the
 * source and device, and stop storing full IP addresses (a masked IP plus a
 * one-way hash for counting unique visitors). Existing rows are converted.
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('audit', function (Blueprint $table) {
            $table->string('type', 10)->default('human')->index()->after('function');
            $table->string('agent')->nullable()->after('type');
            $table->string('referrer')->nullable()->after('agent');
            $table->string('device', 10)->nullable()->after('referrer');
            $table->string('browser', 20)->nullable()->after('device');
            $table->string('os', 20)->nullable()->after('browser');
            $table->string('ip_hash', 64)->nullable()->index()->after('ip');
            $table->index('created_at');
        });

        DB::table('audit')->orderBy('id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                $class = VisitClassifier::classify($row->uagent);
                DB::table('audit')->where('id', $row->id)->update([
                    'type' => $class['type'],
                    'agent' => $class['agent'],
                    'device' => VisitClassifier::device($row->uagent),
                    'browser' => VisitClassifier::browser($row->uagent),
                    'os' => VisitClassifier::os($row->uagent),
                    'ip_hash' => VisitClassifier::hashIp($row->ip),
                    'ip' => VisitClassifier::maskIp($row->ip),
                ]);
            }
        });
    }

    public function down()
    {
        Schema::table('audit', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropColumn(['type', 'agent', 'referrer', 'device', 'browser', 'os', 'ip_hash']);
        });
    }
};
