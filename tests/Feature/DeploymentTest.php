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
}
