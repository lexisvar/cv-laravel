<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralUpdateRequest;
use App\Http\Requests\GeneralCreateRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\General;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class GeneralController extends Controller
{
  public function index()
  {
    $data['generals'] = General::all();

    return view('general.index', $data);
  }

  public function edit($id): View
  {
    return view('general.edit', [
      'general' => General::find($id),
    ]);
  }

  public function new(): View
  {
    return view('general.new');
  }

  public function create(GeneralCreateRequest $request): RedirectResponse
  {
    $general = new General();
    $general->fill($request->validated());
    $general->save();

    return Redirect::route('general.edit',$general->id);
  }

  public function update(GeneralUpdateRequest $request, $id): RedirectResponse
  {
    $general = General::find($id);
    $general->fill($request->validated());
    $general->save();

    return Redirect::route('general.edit',$id);
  }

  public function destroy($id): RedirectResponse
  {
    $general = General::find($id);
    $general->delete();
    return Redirect::to('/general');
  }
}
