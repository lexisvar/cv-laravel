<?php

namespace App\Console\Commands;

use App\Models\Education;
use App\Models\Experience;
use App\Models\General;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Loads the initial CV content from database/content/cv.php.
 *
 * Runs on every deploy but only writes when the CV is empty, so edits made
 * in the admin are never overwritten. Use --force to replace everything.
 */
class LoadCvContent extends Command
{
    protected $signature = 'app:load-cv-content {--force : Replace existing CV content}';

    protected $description = 'Load the initial CV content when the CV is empty';

    public function handle(): int
    {
        $hasContent = General::exists() || Experience::exists() || Project::exists()
            || Skill::exists() || Education::exists();

        if ($hasContent && ! $this->option('force')) {
            $this->line('app:load-cv-content: CV already has content, skipping.');

            return self::SUCCESS;
        }

        $content = require database_path('content/cv.php');

        DB::transaction(function () use ($content) {
            foreach ([General::class, Experience::class, Project::class, Skill::class, Education::class] as $model) {
                $model::query()->delete();
            }

            foreach ($content['generals'] as $key => $value) {
                General::create(['key' => $key, 'value' => $value]);
            }

            foreach ($content['experiences'] as $experience) {
                Experience::create($experience);
            }

            foreach ($content['projects'] as $project) {
                Project::create($project);
            }

            foreach ($content['skills'] as $category => $names) {
                foreach (array_values($names) as $order => $name) {
                    Skill::create(['name' => $name, 'category' => $category, 'sort_order' => $order]);
                }
            }

            foreach ($content['educations'] as $education) {
                Education::create($education);
            }
        });

        $this->info(sprintf(
            'app:load-cv-content: loaded %d settings, %d experiences, %d projects, %d skills, %d education entries.',
            General::count(), Experience::count(), Project::count(), Skill::count(), Education::count()
        ));

        return self::SUCCESS;
    }
}
