<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Education;
use App\Models\Experience;
use App\Models\General;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Support\Str;
use PDF;

class MainController extends Controller
{
    public function index()
    {
        $this->audit('index');

        return view('home', $this->cvData());
    }

    public function simple()
    {
        $this->audit('simple');

        return view('cv', $this->cvData());
    }

    public function createPDF()
    {
        $this->audit('createPDF');

        $data = $this->cvData();
        $filename = Str::slug($data['generals']['name'] ?? 'cv').'-CV.pdf';

        return PDF::loadView('cv', $data)->download($filename);
    }

    /**
     * Everything the public CV views need, loaded in one place.
     */
    private function cvData(): array
    {
        $skills = Skill::orderBy('sort_order')->orderBy('name')->get();

        return [
            'generals' => General::all()->pluck('value', 'key')->all(),
            'skillGroups' => $skills->groupBy(fn ($skill) => $skill->category ?: 'Other'),
            'experiences' => Experience::orderByDesc('start_date')->get(),
            'projects' => Project::orderBy('sort_order')->orderByDesc('start_date')->get(),
            'educations' => Education::orderByDesc('start_date')->get(),
        ];
    }

    private function audit(string $function): void
    {
        Audit::create([
            'ip' => request()->getClientIp(),
            'controller' => 'Main',
            'function' => $function,
            'uagent' => Str::limit((string) request()->header('User-Agent'), 250, ''),
        ]);
    }
}
