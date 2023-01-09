<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Education;

class MainController extends Controller
{
  public function index()
  {
    $data['skills'] = Skill::all();
    $data['experiences'] = Experience::all();
    $data['educations'] = Education::all();  
  
    return view('home', $data);
  }
}
