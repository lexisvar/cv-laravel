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
    $data['experiences'] = Experience::all();
    $data['educations'] = Education::all();  
  
    return view('home', $data);
  }

  public function createPDF(){
    $data['skills'] = Skill::all();
    $pdf = PDF::loadView('cv', $data);
    return $pdf->download('cv', $data);
  }

  public function simple(){
    $data['skills'] = Skill::all();
    return view('cv', $data);
  }
}
