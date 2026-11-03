<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralUpdateRequest;
use App\Http\Requests\GeneralCreateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\General;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class GeneralController extends Controller
{
  public const THEMES = ['system' => 'System (follows each visitor\'s device)', 'light' => 'Light', 'dark' => 'Dark'];

  public function index()
  {
    $data['generals'] = General::all();
    $data['themes'] = self::THEMES;
    $data['theme'] = General::where('key', 'theme')->value('value') ?: 'system';

    return view('general.index', $data);
  }

  public function edit($id): View
  {
    return view('general.edit', [
      'general' => General::findOrFail($id),
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
    $general = General::findOrFail($id);
    $general->fill($request->validated());
    $general->save();

    return Redirect::route('general.edit',$id);
  }

  public function destroy($id): RedirectResponse
  {
    $general = General::findOrFail($id);
    $general->delete();
    return Redirect::to('/general');
  }

  /**
   * Site appearance for the public page: system, light or dark.
   */
  public function theme(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'theme' => ['required', 'in:'.implode(',', array_keys(self::THEMES))],
    ]);

    General::updateOrCreate(['key' => 'theme'], ['value' => $validated['theme']]);

    return Redirect::route('general')->with('status', 'theme-updated');
  }
}
