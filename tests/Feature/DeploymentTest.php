<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class DeploymentTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        foreach (['ADMIN_EMAIL', 'ADMIN_PASSWORD', 'ADMIN_NAME'] as $key) {
            putenv($key);
            unset($_ENV[$key], $_SERVER[$key]);
        }
        parent::tearDown();
    }

    private function setEnv(array $values): void
    {
        foreach ($values as $key => $value) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }

    public function test_health_check(): void
    {
        $this->get('/health')->assertOk()->assertExactJson(['status' => 'ok']);
    }

    public function test_create_admin_is_idempotent(): void
    {
        $this->setEnv(['ADMIN_EMAIL' => 'admin@example.com', 'ADMIN_PASSWORD' => 'a-long-password-123']);

        $this->artisan('app:create-admin')->assertSuccessful();
        $this->artisan('app:create-admin')->assertSuccessful();

        $this->assertSame(1, User::count());
        $user = User::first();
        $this->assertTrue(Hash::check('a-long-password-123', $user->password));
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_create_admin_never_changes_an_existing_password(): void
    {
        User::factory()->create(['email' => 'admin@example.com', 'password' => Hash::make('original-password-1')]);
        $this->setEnv(['ADMIN_EMAIL' => 'admin@example.com', 'ADMIN_PASSWORD' => 'another-password-22']);

        $this->artisan('app:create-admin')->assertSuccessful();

        $this->assertTrue(Hash::check('original-password-1', User::first()->password));
    }

    public function test_create_admin_skips_without_variables_and_rejects_short_passwords(): void
    {
        $this->artisan('app:create-admin')->assertSuccessful();
        $this->assertSame(0, User::count());

        $this->setEnv(['ADMIN_EMAIL' => 'admin@example.com', 'ADMIN_PASSWORD' => 'short']);
        $this->artisan('app:create-admin')->assertFailed();
        $this->assertSame(0, User::count());
    }

    public function test_https_urls_are_forced_when_configured(): void
    {
        config(['app.force_https' => true]);
        (new \App\Providers\AppServiceProvider($this->app))->boot();

        $this->assertStringStartsWith('https://', URL::to('/images/alexisvargas.jpeg'));
    }

    public function test_proxied_https_requests_are_recognised(): void
    {
        $this->get('/health', ['X-Forwarded-Proto' => 'https', 'X-Forwarded-For' => '203.0.113.9'])->assertOk();
        $this->assertTrue(request()->isSecure());
    }

    public function test_cv_content_loads_once_and_never_overwrites_edits(): void
    {
        $this->artisan('app:load-cv-content')->assertSuccessful();

        $this->assertSame('Alexis Vargas', \App\Models\General::where('key', 'name')->value('value'));
        $this->assertGreaterThan(5, \App\Models\Experience::count());
        $this->assertSame('Caissify', \App\Models\Project::orderBy('sort_order')->value('name'));

        // An edit made in the admin survives the next deploy.
        \App\Models\General::where('key', 'name')->update(['value' => 'Edited']);
        $this->artisan('app:load-cv-content')->assertSuccessful();
        $this->assertSame('Edited', \App\Models\General::where('key', 'name')->value('value'));

        $this->artisan('app:load-cv-content', ['--force' => true])->assertSuccessful();
        $this->assertSame('Alexis Vargas', \App\Models\General::where('key', 'name')->value('value'));
    }

    public function test_cv_content_never_mentions_visas(): void
    {
        $this->artisan('app:load-cv-content')->assertSuccessful();

        foreach (['/', '/simple'] as $url) {
            $html = strtolower($this->get($url)->assertOk()->getContent());
            foreach (['visa', '482', 'sponsor', 'work rights', '261313'] as $word) {
                $this->assertStringNotContainsString($word, $html, "'{$word}' found on {$url}");
            }
        }
    }

    public function test_links_use_the_main_domain_in_production(): void
    {
        config(['app.force_https' => true, 'app.url' => 'https://lexisvar.me']);
        (new \App\Providers\AppServiceProvider($this->app))->boot();

        $this->assertSame('https://lexisvar.me/cv.pdf', URL::to('/cv.pdf'));
    }
}
