<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('projects.index', [
            'projects' => Project::orderBy('sort_order')->orderByDesc('start_date')->get(),
        ]);
    }

    public function new(): View
    {
        return view('projects.new');
    }

    public function edit($id): View
    {
        return view('projects.edit', [
            'project' => Project::findOrFail($id),
        ]);
    }

    public function create(ProjectRequest $request): RedirectResponse
    {
        $project = Project::create($this->payload($request));

        return Redirect::route('project.edit', $project->id)->with('status', 'project-updated');
    }

    public function update(ProjectRequest $request, $id): RedirectResponse
    {
        Project::findOrFail($id)->update($this->payload($request));

        return Redirect::route('project.edit', $id)->with('status', 'project-updated');
    }

    public function destroy($id): RedirectResponse
    {
        Project::findOrFail($id)->delete();

        return Redirect::route('projects');
    }

    private function payload(ProjectRequest $request): array
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
