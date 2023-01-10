<?php

namespace App\Http\Controllers;

use App\Http\Requests\EducationUpdateRequest;
use App\Http\Requests\EducationCreateRequest;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use App\Models\Education;

class EducationController extends Controller
{
  public function index()
  {
    $data['educations'] = Education::orderBy('start_date','desc')->get();
  
    return view('education.index', $data);
  }

  public function edit($id): View
  {
    return view('education.edit', [
      'education' => Education::find($id),
    ]);
  }

  public function new(): View
  {
    return view('education.new');
  }

  public function create(EducationCreateRequest $request): RedirectResponse
  {
    $education = new Education();
    $education->fill($request->validated());
    $education->save();

    return Redirect::route('education.edit',$education->id)->with('status', 'education-updated');
  }

  public function update(EducationUpdateRequest $request, $id): RedirectResponse
  {
    $education = Education::find($id);
    $education->fill($request->validated());
    $education->save();

    return Redirect::route('education.edit',$id)->with('status', 'education-updated');
  }

  public function destroy($id): RedirectResponse
  {
    $education = Education::find($id);
    $education->delete();
    return Redirect::to('/education');
  }
}
