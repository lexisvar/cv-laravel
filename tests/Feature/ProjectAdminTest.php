<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_manage_projects(): void
    {
        $this->get('/projects')->assertRedirect('/login');
        $this->post('/projects/create', ['name' => 'X', 'description' => 'Y'])->assertRedirect('/login');
        $this->assertDatabaseCount('projects', 0);
    }

    public function test_admin_can_create_update_and_delete_a_project(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/projects')->assertOk();
        $this->actingAs($user)->get('/projects/new')->assertOk();

        $this->actingAs($user)->post('/projects/create', [
            'name' => 'Caissify',
            'role' => 'Founder & Lead Engineer',
            'stack' => 'Django, Vue',
            'description' => '- Tournament engine',
            'url' => 'https://example.com',
        ])->assertRedirect();

        $project = Project::firstOrFail();
        $this->assertSame(0, $project->sort_order);
        $this->assertSame(['Django', 'Vue'], $project->stackList());

        $this->actingAs($user)->get("/projects/{$project->id}/edit")->assertOk()->assertSee('Caissify');

        $this->actingAs($user)->patch("/projects/{$project->id}/update", [
            'name' => 'Caissify',
            'description' => 'Updated',
            'sort_order' => 2,
        ])->assertRedirect();
        $this->assertSame('Updated', $project->fresh()->description);

        $this->actingAs($user)->delete("/projects/{$project->id}")->assertRedirect('/projects');
        $this->assertDatabaseCount('projects', 0);
    }

    public function test_project_validation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/projects/create', [
            'name' => '',
            'description' => '',
            'url' => 'not-a-url',
        ])->assertSessionHasErrors(['name', 'description', 'url']);
    }

    public function test_missing_records_return_404(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/projects/999/edit')->assertNotFound();
        $this->actingAs($user)->get('/experience/999/edit')->assertNotFound();
    }

    public function test_skill_without_percent_can_be_created(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/skills/create', [
            'name' => 'Django',
            'category' => 'Backend',
        ])->assertRedirect();

        $this->assertDatabaseHas('skills', ['name' => 'Django', 'category' => 'Backend', 'percent' => null, 'sort_order' => 0]);
    }
}
