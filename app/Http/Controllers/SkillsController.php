<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkillUpdateRequest;
use App\Http\Requests\SkillCreateRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\Skill;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class SkillsController extends Controller
{
  public function index()
  {
    $data['skills'] = Skill::orderBy('category')->orderBy('sort_order')->orderBy('name')->get();
  
    return view('skills.index', $data);
  }

  public function edit($id): View
  {
    return view('skills.edit', [
      'skill' => Skill::findOrFail($id),
    ]);
  }

  public function new(): View
  {
    return view('skills.new');
  }

  public function create(SkillCreateRequest $request): RedirectResponse
  {
    $skill = new Skill();
    $skill->fill($this->payload($request));
    $skill->save();

    return Redirect::route('skill.edit',$skill->id)->with('status', 'skill-updated');
  }

  public function update(SkillUpdateRequest $request, $id): RedirectResponse
  {
    $skill = Skill::findOrFail($id);
    $skill->fill($this->payload($request));
    $skill->save();

    return Redirect::route('skill.edit',$id)->with('status', 'skill-updated');
  }

  public function destroy($id): RedirectResponse
  {
    $skill = Skill::findOrFail($id);
    $skill->delete();
    return Redirect::to('/skills');
  }

  private function payload($request): array
  {
    $data = $request->validated();
    $data['sort_order'] = $data['sort_order'] ?? 0;

    return $data;
  }
}
