<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExperienceUpdateRequest;
use App\Http\Requests\ExperienceCreateRequest;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use App\Models\Experience;

class ExperienceController extends Controller
{
  public function index()
  {
    $data['experiences'] = Experience::all();
  
    return view('experience.index', $data);
  }

  public function edit($id): View
  {
    return view('experience.edit', [
      'experience' => Experience::find($id),
    ]);
  }

  public function new(): View
  {
    return view('experience.new');
  }

  public function create(ExperienceCreateRequest $request): RedirectResponse
  {
    $experience = new Experience();
    $experience->fill($request->validated());
    $experience->save();

    return Redirect::route('experience.edit',$experience->id)->with('status', 'experience-updated');
  }

  public function update(ExperienceUpdateRequest $request, $id): RedirectResponse
  {
    $experience = Experience::find($id);
    $experience->fill($request->validated());
    $experience->save();

    return Redirect::route('experience.edit',$id)->with('status', 'experience-updated');
  }

  public function destroy($id): RedirectResponse
  {
    $experience = Experience::find($id);
    $experience->delete();
    return Redirect::to('/experience');
  }
}
