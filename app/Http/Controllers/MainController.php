<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Education;
use App\Models\Experience;
use App\Models\General;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
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

    /**
     * /export downloads the CV; /cv.pdf opens it in the browser (shareable link).
     */
    public function createPDF(Request $request)
    {
        $inline = $request->routeIs('cv.pdf');
        $this->audit($inline ? 'viewPDF' : 'createPDF');

        $data = $this->cvData();
        $name = $data['generals']['name'] ?? 'CV';
        $filename = Str::slug($name).'-CV.pdf';

        $dompdf = PDF::loadView('cv', $data)->setPaper('a4')->getDomPDF();
        $dompdf->addInfo('Title', "{$name} – CV");
        $dompdf->addInfo('Author', $name);
        $dompdf->addInfo('Subject', $data['generals']['occupation'] ?? 'Curriculum vitae');
        $dompdf->render();

        // Footer on every page: name on the left, page x of y on the right.
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont('Helvetica');
        $grey = [0.39, 0.45, 0.55];
        $canvas->page_text(42, $canvas->get_height() - 28, "{$name} · ".preg_replace('#^https?://#', '', url('/')), $font, 7.5, $grey);
        $canvas->page_text($canvas->get_width() - 92, $canvas->get_height() - 28, 'Page {PAGE_NUM} of {PAGE_COUNT}', $font, 7.5, $grey);

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => ($inline ? 'inline' : 'attachment').'; filename="'.$filename.'"',
            'Cache-Control' => 'no-store',
        ]);
    }

    /**
     * Everything the public CV views need, loaded in one place.
     */
    private function cvData(): array
    {
        $generals = General::all()->pluck('value', 'key')->all();
        $skills = Skill::orderBy('sort_order')->orderBy('name')->get();

        return [
            'generals' => $generals,
            'skillGroups' => $this->groupSkills($skills, $generals['skill_categories'] ?? null),
            'experiences' => Experience::orderByDesc('start_date')->get(),
            'projects' => Project::orderBy('sort_order')->orderByDesc('start_date')->get(),
            'educations' => Education::orderByDesc('start_date')->get(),
        ];
    }

    /**
     * Group skills by category. Categories listed (comma separated) in the
     * "skill_categories" setting come first, in that order; the rest follow
     * alphabetically, with uncategorised skills last.
     */
    private function groupSkills($skills, ?string $order)
    {
        $preferred = $order
            ? array_map('trim', explode(',', $order))
            : ['Backend', 'Frontend', 'Frontend & Mobile', 'Mobile', 'Data', 'Cloud & DevOps', 'DevOps', 'AI', 'Tools'];
        $rank = array_flip($preferred);

        return $skills
            ->groupBy(fn ($skill) => $skill->category ?: 'Other')
            ->sortBy(fn ($group, $category) => [
                $category === 'Other' ? 2 : (isset($rank[$category]) ? 0 : 1),
                $rank[$category] ?? 0,
                $category,
            ]);
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
