<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Education;
use App\Models\General;
use PDF;

class MainController extends Controller
{
  public function index()
  {
    Audit::create(
      [
        'ip' => request()->getClientIp(),
        'controller' => 'Main',
        'function' => 'index',
        'uagent' => request()->header('User-Agent')
      ],
    );

    $data['skills'] = Skill::all();
    $data['experiences'] = Experience::orderBy('start_date','desc')->get();
    $data['educations'] = Education::orderBy('start_date','desc')->get();
    $data['generals'] = General::all()->pluck('value', 'key')->all();

    return view('home', $data);
  }

  public function createPDF(){
    Audit::create(
      [
        'ip' => request()->getClientIp(),
        'controller' => 'Main',
        'function' => 'createPDF',
        'uagent' => request()->header('User-Agent')
      ],
    );
    $data['skills'] = Skill::all();
    $data['experiences'] = Experience::orderBy('start_date','desc')->get();
    $data['educations'] = Education::orderBy('start_date','desc')->get();
    $pdf = PDF::loadView('cv', $data);
    return $pdf->download('cv', $data);
  }

  public function simple(){
    Audit::create(
      [
        'ip' => request()->getClientIp(),
        'controller' => 'Main',
        'function' => 'simple',
        'uagent' => request()->header('User-Agent')
      ],
    );
    $data['skills'] = Skill::all();
    $data['experiences'] = Experience::orderBy('start_date','desc')->get();
    $data['educations'] = Education::orderBy('start_date','desc')->get();
    return view('cv', $data);
  }
}
