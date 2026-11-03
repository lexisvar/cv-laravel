<?php

namespace Tests\Feature;

use App\Models\Education;
use App\Models\Experience;
use App\Models\General;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCvTest extends TestCase
{
    use RefreshDatabase;

    private function seedCv(): void
    {
        foreach ([
            'name' => 'Alexis Vargas',
            'occupation' => 'Software Engineer',
            'biography' => "Backend engineer.\n- Laravel APIs\n- Rails services",
            'email' => 'test@example.com',
            'address' => 'Sydney, Australia',
            'language' => 'Spanish, English',
            'phone' => '+61 400 000 000',
        ] as $key => $value) {
            General::create(['key' => $key, 'value' => $value]);
        }

        Skill::create(['name' => 'Laravel', 'category' => 'Backend', 'percent' => 90]);
        Skill::create(['name' => 'Vue.js', 'category' => 'Frontend']);

        Experience::create([
            'start_date' => '2020-01-01',
            'finish_date' => '2022-02-01',
            'occupation' => 'PHP Laravel Developer',
            'company' => 'Grupo Bien Pensado',
            'description' => "- Built REST APIs\n- Designed PostgreSQL schemas",
        ]);

        Project::create([
            'name' => 'Caissify',
            'role' => 'Founder & Lead Engineer',
            'stack' => 'Django, Vue, PostgreSQL, Flutter',
            'description' => 'Chess learning platform.',
            'url' => 'https://example.com',
        ]);

        Education::create([
            'start_date' => '2009-08-01',
            'finish_date' => '2026-10-30',
            'study_name' => 'Systems Engineering',
            'study_title' => 'Ingeniero de Sistemas',
            'college' => 'Corporación Universitaria Remington',
            'description' => 'Bachelor degree.',
        ]);
    }

    public function test_home_page_renders_all_sections(): void
    {
        $this->seedCv();

        $this->get('/')
            ->assertOk()
            ->assertSee('<title>Alexis Vargas · Software Engineer</title>', false)
            ->assertSee('Grupo Bien Pensado')
            ->assertSee('<li>Built REST APIs</li>', false)
            ->assertSee('Caissify')
            ->assertSee('Founder &amp; Lead Engineer', false)
            ->assertSee('Backend')
            ->assertSee('Corporación Universitaria Remington')
            ->assertSee('Present');
    }

    public function test_home_page_does_not_show_skill_percentages_or_template_placeholders(): void
    {
        $this->seedCv();

        $this->get('/')
            ->assertOk()
            ->assertDontSee('90%')
            ->assertDontSee('Habitasse venenatis')
            ->assertDontSee('formspree');
    }

    public function test_home_page_renders_without_any_content(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_user_content_is_escaped(): void
    {
        $this->seedCv();
        Experience::first()->update(['description' => "- <script>alert(1)</script>"]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_phone_can_be_hidden(): void
    {
        $this->seedCv();
        General::create(['key' => 'show_phone', 'value' => '0']);

        $this->get('/')->assertOk()->assertDontSee('+61 400 000 000');
    }

    public function test_simple_view_renders(): void
    {
        $this->seedCv();

        $this->get('/simple')->assertOk()->assertSee('Caissify')->assertSee('Backend');
    }

    public function test_pdf_download_has_a_proper_filename(): void
    {
        $this->seedCv();

        $response = $this->get('/export');

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('alexis-vargas-CV.pdf', $response->headers->get('Content-Disposition'));
    }

    public function test_visits_are_audited(): void
    {
        $this->get('/');

        $this->assertDatabaseHas('audit', ['controller' => 'Main', 'function' => 'index']);
    }
}
