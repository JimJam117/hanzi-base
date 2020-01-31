<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $charCount = \App\Character::count();
        $chars = \App\Character::all();

        $chars = json_encode($chars);

        return view('home', compact('charCount', 'chars'));
    }
}
