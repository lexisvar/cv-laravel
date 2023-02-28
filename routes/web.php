<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\EducationController;
use Illuminate\Support\Facades\Route;
use App\Models\Audit;

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
  return view('dashboard',['visits' => Audit::orderBy('created_at','desc')->get()]);
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

  Route::get('/experience', [ExperienceController::class, 'index'])->name('experience');
  Route::get('/experience/new', [ExperienceController::class, 'new'])->name('experience.new');
  Route::get('/experience/{id}/edit', [ExperienceController::class, 'edit'])->name('experience.edit');
  Route::post('/experience/create', [ExperienceController::class, 'create'])->name('experience.create');
  Route::patch('/experience/{id}/update', [ExperienceController::class, 'update'])->name('experience.update');
  Route::delete('/experience/{id}', [ExperienceController::class, 'destroy'])->name('experience.destroy');

  Route::get('/education', [EducationController::class, 'index'])->name('education');
  Route::get('/education/new', [EducationController::class, 'new'])->name('education.new');
  Route::get('/education/{id}/edit', [EducationController::class, 'edit'])->name('education.edit');
  Route::post('/education/create', [EducationController::class, 'create'])->name('education.create');
  Route::patch('/education/{id}/update', [EducationController::class, 'update'])->name('education.update');
  Route::delete('/education/{id}', [EducationController::class, 'destroy'])->name('education.destroy');

});

require __DIR__.'/auth.php';