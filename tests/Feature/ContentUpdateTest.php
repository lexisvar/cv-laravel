<?php

namespace Tests\Feature;

use App\Models\General;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentUpdateTest extends TestCase
{
    use RefreshDatabase;

    private function migration(): object
    {
        return require database_path('migrations/2026_11_04_000002_update_cv_content_about_and_links.php');
    }

    public function test_live_site_content_is_upgraded_but_admin_edits_are_kept(): void
    {
        $migration = $this->migration();
        $original = (new \ReflectionClassConstant($migration, 'ORIGINAL'))->getValue();

        // A site that loaded the first version of the content...
        General::create(['key' => 'biography', 'value' => $original['biography']]);
        General::create(['key' => 'pdf_summary', 'value' => $original['pdf_summary']]);
        // ...where the owner already edited the highlights in the admin.
        General::create(['key' => 'highlights', 'value' => 'My own | highlight']);
        Project::create(['name' => 'Caissify', 'description' => 'x', 'sort_order' => 1]);

        $migration->up();

        $content = require database_path('content/cv.php');
        $this->assertSame($content['generals']['biography'], General::where('key', 'biography')->value('value'));
        $this->assertSame($content['generals']['pdf_summary'], General::where('key', 'pdf_summary')->value('value'));
        $this->assertSame('My own | highlight', General::where('key', 'highlights')->value('value'));
        $this->assertStringContainsString('apps.apple.com', Project::where('name', 'Caissify')->value('links'));
    }

    public function test_new_about_text_leads_with_current_work(): void
    {
        $this->artisan('app:load-cv-content')->assertSuccessful();
        $bio = General::where('key', 'biography')->value('value');

        $this->assertStringStartsWith('I build web, mobile and backend software end to end.', $bio);
        $this->assertStringNotContainsString('2012–2023', $bio);
        $this->assertStringContainsString('chess player and coach', $bio);
        $this->assertStringContainsString('Claude Code', $bio);
    }

    public function test_store_links_are_shown_on_the_site_and_in_the_pdf(): void
    {
        $this->artisan('app:load-cv-content')->assertSuccessful();

        $this->get('/')
            ->assertOk()
            ->assertSee('href="https://apps.apple.com/us/app/caissify/id6755508097"', false)
            ->assertSee('Google Play');
        $this->get('/simple')->assertOk()->assertSee('App Store');
    }

    public function test_only_http_links_are_rendered(): void
    {
        $project = new Project(['links' => "Good | https://example.com\nBad | javascript:alert(1)\nno separator\n | https://empty-label.example"]);

        $this->assertSame([['label' => 'Good', 'url' => 'https://example.com']], $project->linkList());
    }

    public function test_education_shows_only_the_completion_date(): void
    {
        $this->artisan('app:load-cv-content')->assertSuccessful();

        foreach (['/', '/simple'] as $url) {
            $this->get($url)->assertOk()
                ->assertSee('Bachelor of Systems Engineering')
                ->assertSee('Oct 2026')
                ->assertDontSee('2009');
        }
    }

    public function test_existing_degree_loses_its_start_year(): void
    {
        \App\Models\Education::create([
            'study_name' => 'Bachelor of Systems Engineering', 'study_title' => 'Ingeniero de Sistemas',
            'college' => 'Remington', 'start_date' => '2009-08-01', 'finish_date' => '2026-10-30',
            'description' => 'Degree completed while working in industry. Conferred 30 October 2026.',
        ]);

        (require database_path('migrations/2026_11_04_000003_make_education_start_date_optional.php'))->up();

        $degree = \App\Models\Education::first();
        $this->assertNull($degree->start_date);
        $this->assertSame('', $degree->description);
    }

    public function test_admin_can_save_education_without_a_start_date(): void
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user)->post('/education/create', [
            'study_name' => 'Bachelor', 'study_title' => 'Ingeniero', 'college' => 'Remington',
            'finish_date' => '2026-10-30', 'description' => 'x',
        ])->assertSessionHasNoErrors();

        $this->assertNull(\App\Models\Education::first()->start_date);
    }

    public function test_shore_placeholder_is_replaced_with_contract_details(): void
    {
        \App\Models\Experience::create([
            'occupation' => 'Ruby on Rails Developer', 'company' => 'Shore',
            'start_date' => '2022-02-01', 'finish_date' => '2023-03-01', 'description' => '- old',
        ]);
        $migration = database_path('migrations/2026_11_09_000001_update_shore_experience.php');

        (require $migration)->up();

        $shore = \App\Models\Experience::first();
        $this->assertSame('Solution Engineer / Product Engineer', $shore->occupation);
        $this->assertStringStartsWith('Shore Consulting Group', $shore->company);
        $this->assertStringStartsWith('2022-02-14', (string) $shore->start_date);
        $this->assertStringStartsWith('2024-01-31', (string) $shore->finish_date);
        $this->assertStringContainsString('Python/Django', $shore->description);
    }

    public function test_edited_shore_entry_is_left_alone(): void
    {
        \App\Models\Experience::create([
            'occupation' => 'My own title', 'company' => 'Shore',
            'start_date' => '2022-02-01', 'finish_date' => '2023-03-01', 'description' => '- mine',
        ]);

        (require database_path('migrations/2026_11_09_000001_update_shore_experience.php'))->up();

        $this->assertSame('My own title', \App\Models\Experience::first()->occupation);
    }

    public function test_shore_details_appear_on_site_and_pdf_page(): void
    {
        $this->artisan('app:load-cv-content')->assertSuccessful();

        foreach (['/', '/simple'] as $url) {
            $this->get($url)->assertOk()
                ->assertSee('Solution Engineer / Product Engineer')
                ->assertSee('Shore Consulting Group')
                ->assertSee('Feb 2022')
                ->assertSee('Jan 2024');
        }
    }

    public function test_caissify_description_is_upgraded_to_version_2(): void
    {
        $file = database_path('migrations/2026_11_10_000001_update_caissify_v2.php');
        $migration = require $file;
        $original = (new \ReflectionClassConstant($migration, 'ORIGINAL'))->getValue();

        \App\Models\Project::create(['name' => 'Caissify', 'description' => $original, 'sort_order' => 1]);
        \App\Models\Project::create(['name' => 'Other', 'description' => 'untouched', 'sort_order' => 2]);

        $migration->up();

        $this->assertStringContainsStringIgnoringCase('version 2.0', \App\Models\Project::where('name', 'Caissify')->value('description'));
        $this->assertSame('untouched', \App\Models\Project::where('name', 'Other')->value('description'));
    }

    public function test_cv_is_refocused_on_experience_and_infrastructure(): void
    {
        $migration = require database_path('migrations/2026_11_10_000002_refocus_cv_on_experience.php');
        $previous = (new \ReflectionClassConstant($migration, 'PREVIOUS'))->getValue();

        foreach ($previous['generals'] as $key => $value) {
            \App\Models\General::create(['key' => $key, 'value' => $value]);
        }
        \App\Models\General::where('key', 'highlights')->update(['value' => 'Edited | by me']);
        \App\Models\Project::create(['name' => 'Caissify', 'description' => $previous['caissify'], 'sort_order' => 1]);
        \App\Models\Project::create(['name' => $previous['pairings_name'], 'description' => $previous['pairings'], 'sort_order' => 2]);

        $migration->up();

        $bio = \App\Models\General::where('key', 'biography')->value('value');
        $this->assertStringContainsString('Shore Consulting Group', $bio);
        $this->assertStringNotContainsString('pairing engine', $bio);
        $this->assertSame('Edited | by me', \App\Models\General::where('key', 'highlights')->value('value'));
        $this->assertStringContainsString('Infrastructure:', \App\Models\Project::where('name', 'Caissify')->value('description'));
        $this->assertStringContainsString('Google Play first', \App\Models\Project::where('name', 'Caissify')->value('description'));
        $this->assertSame(1, \App\Models\Project::where('name', 'caissify-pairings')->count());
        $this->assertStringNotContainsString('10,000', \App\Models\Project::where('name', 'caissify-pairings')->value('description'));
    }
}
