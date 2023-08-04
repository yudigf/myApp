<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\FlareClient\View;

class DemoController extends Controller
{
    public function index()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
