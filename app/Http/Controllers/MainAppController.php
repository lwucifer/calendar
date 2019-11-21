<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainAppController extends Controller
{
    public function dashboard(){
        return view('app.angular');
    }
}
