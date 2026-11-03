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
}
