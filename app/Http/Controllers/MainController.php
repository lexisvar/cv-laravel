<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Education;
use PDF;

class MainController extends Controller
{
  public function index()
  {
    $data['skills'] = Skill::all();
    $data['experiences'] = Experience::orderBy('start_date','desc')->get();
    $data['educations'] = Education::orderBy('start_date','desc')->get();
  
    return view('home', $data);
  }

  public function createPDF(){
    $data['skills'] = Skill::all();
    $data['experiences'] = Experience::orderBy('start_date','desc')->get();
    $data['educations'] = Education::orderBy('start_date','desc')->get();
    $pdf = PDF::loadView('cv', $data);
    return $pdf->download('cv', $data);
  }

  public function simple(){
    $data['skills'] = Skill::all();
    $data['experiences'] = Experience::orderBy('start_date','desc')->get();
    $data['educations'] = Education::orderBy('start_date','desc')->get();
    return view('cv', $data);
  }
}
