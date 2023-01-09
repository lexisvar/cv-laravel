<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\Experience;

class MainController extends Controller
{
  public function index()
  {
    $data['skills'] = Skill::all();
    $data['experiences'] = Experience::all();  
  
    return view('home', $data);
  }
}
