<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function index()
    {
        $chars = \App\Character::all();

        return view('character.index', compact('chars'));
    }
}
