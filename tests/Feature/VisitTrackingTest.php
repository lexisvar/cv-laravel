<?php

namespace Tests\Feature;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitTrackingTest extends TestCase
{
    use RefreshDatabase;

    private const CHROME = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36';

    public function test_visits_are_classified_and_ip_is_masked(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '79.127.228.17'])
            ->get('/', ['User-Agent' => self::CHROME, 'Referer' => 'https://www.linkedin.com/feed/'])
            ->assertOk();
        $this->get('/', ['User-Agent' => 'LinkedInBot/1.0 (compatible; Mozilla/5.0)'])->assertOk();
        $this->get('/simple', ['User-Agent' => 'curl/8.5.0'])->assertOk();

        $person = Audit::where('type', 'human')->firstOrFail();
        $this->assertSame('79.127.228.0', $person->ip);
        $this->assertSame(64, strlen($person->ip_hash));
        $this->assertSame(['linkedin.com', 'desktop', 'Chrome', 'macOS'], [$person->referrer, $person->device, $person->browser, $person->os]);
        $this->assertSame('LinkedIn', Audit::where('type', 'preview')->value('agent'));
        $this->assertSame('curl', Audit::where('type', 'bot')->value('agent'));
    }

    public function test_owner_visits_are_not_logged(): void
    {
        $this->actingAs(User::factory()->create())->get('/', ['User-Agent' => self::CHROME])->assertOk();

        $this->assertSame(0, Audit::count());
    }

    public function test_dashboard_summary_and_tabs(): void
    {
        foreach (['10.0.0.1', '10.0.0.2', '10.0.0.1'] as $ip) {
            $this->withServerVariables(['REMOTE_ADDR' => $ip])->get('/', ['User-Agent' => self::CHROME]);
        }
        $this->get('/cv.pdf', ['User-Agent' => self::CHROME]);
        $this->get('/', ['User-Agent' => 'Slackbot-LinkExpanding 1.0']);
        $this->get('/', ['User-Agent' => 'curl/8.5.0']);

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard')->assertOk();
        $this->assertSame(['views' => 3, 'visitors' => 2, 'cv' => 1, 'previews' => 1, 'bots' => 1], $response->viewData('summary'));
        $this->assertSame(4, $response->viewData('visits')->total(), 'People tab shows only people');

        $this->assertSame(1, $this->actingAs($user)->get('/dashboard?type=bot')->viewData('visits')->total());
        $this->assertSame(1, $this->actingAs($user)->get('/dashboard?type=preview')->viewData('visits')->total());
        $this->assertSame(6, $this->actingAs($user)->get('/dashboard?type=all')->viewData('visits')->total());
        $this->actingAs($user)->get('/dashboard?type=nonsense')->assertOk()->assertViewHas('tab', 'human');
    }

    public function test_dashboard_requires_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_old_visits_are_pruned(): void
    {
        $old = Audit::create(['ip' => '1.2.3.0', 'controller' => 'Main', 'function' => 'index', 'type' => 'human']);
        $old->forceFill(['created_at' => now()->subDays(400)])->save();
        Audit::create(['ip' => '1.2.3.0', 'controller' => 'Main', 'function' => 'index', 'type' => 'human']);

        $this->artisan('app:prune-visits')->assertSuccessful();

        $this->assertSame(1, Audit::count());
    }
}
