<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Skill;

class MainController extends Controller
{
  public function index()
  {
    $data['skills'] = Skill::all();  
    return view('home', $data);
  }
}
