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
    $data['skills'] = Skill::all();
  
    return view('skills.index', $data);
  }

  public function edit($id): View
  {
    return view('skills.edit', [
      'skill' => Skill::find($id),
    ]);
  }

  public function new(): View
  {
    return view('skills.new');
  }

  public function create(SkillCreateRequest $request): RedirectResponse
  {
    $skill = new Skill();
    $skill->fill($request->validated());
    $skill->save();

    return Redirect::route('skill.edit',$skill->id)->with('status', 'skill-updated');
  }

  public function update(SkillUpdateRequest $request, $id): RedirectResponse
  {
    $skill = Skill::find($id);
    $skill->fill($request->validated());
    $skill->save();

    return Redirect::route('skill.edit',$id)->with('status', 'skill-updated');
  }

  public function destroy($id): RedirectResponse
  {
    $skill = Skill::find($id);
    $skill->delete();
    return Redirect::to('/skills');
  }
}
