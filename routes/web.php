<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\SkillsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MainController::class, 'index']);
Route::get('/simple', [MainController::class, 'simple']);
Route::get('/export', [MainController::class, 'createPDF']);

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  Route::get('/skills', [SkillsController::class, 'index'])->name('skills');
  Route::get('/skills/new', [SkillsController::class, 'new'])->name('skill.new');
  Route::get('/skills/{id}/edit', [SkillsController::class, 'edit'])->name('skill.edit');
  Route::post('/skills/create', [SkillsController::class, 'create'])->name('skill.create');
  Route::patch('/skills/{id}/update', [SkillsController::class, 'update'])->name('skill.update');
  Route::delete('/skills/{id}', [SkillsController::class, 'destroy'])->name('skill.destroy');

});

require __DIR__.'/auth.php';
