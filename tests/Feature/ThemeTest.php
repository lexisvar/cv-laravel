<?php

namespace Tests\Feature;

use App\Models\General;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_system_theme_follows_the_visitor(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<meta name="color-scheme" content="light dark">', false)
            ->assertDontSee('data-theme=', false);
    }

    public function test_forced_light_and_dark_themes(): void
    {
        foreach (['light', 'dark'] as $theme) {
            General::updateOrCreate(['key' => 'theme'], ['value' => $theme]);

            $this->get('/')
                ->assertOk()
                ->assertSee('<html lang="en-AU" data-theme="'.$theme.'">', false)
                ->assertSee('<meta name="color-scheme" content="'.$theme.'">', false);
        }
    }

    public function test_unknown_stored_value_falls_back_to_system(): void
    {
        General::create(['key' => 'theme', 'value' => '"><script>']);

        $this->get('/')->assertOk()->assertDontSee('data-theme=', false)->assertDontSee('"><script>', false);
    }

    public function test_admin_can_change_the_theme(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/general')->assertOk()->assertSee('Appearance');

        $this->actingAs($user)->patch('/general/theme', ['theme' => 'dark'])->assertRedirect('/general');
        $this->assertSame('dark', General::where('key', 'theme')->value('value'));

        $this->actingAs($user)->patch('/general/theme', ['theme' => 'system'])->assertRedirect('/general');
        $this->assertSame('system', General::where('key', 'theme')->value('value'));
        $this->assertSame(1, General::where('key', 'theme')->count());
    }

    public function test_invalid_theme_is_rejected_and_guests_are_blocked(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch('/general/theme', ['theme' => 'purple'])->assertSessionHasErrors('theme');
        $this->assertNull(General::where('key', 'theme')->value('value'));

        auth()->logout();
        $this->patch('/general/theme', ['theme' => 'dark'])->assertRedirect('/login');
        $this->assertNull(General::where('key', 'theme')->value('value'));
    }
}
