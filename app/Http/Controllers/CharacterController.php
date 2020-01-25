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

    public function show($char) {
       
        // find the character
        $charObj = null;
        $charObj = \App\Character::where('simp_char', $char)
        ->orWhere('trad_char', $char)
        ->orWhere('id', $char)
        ->orWhere('freq', $char) 
        ->orWhere('keyword', $char)->firstOrFail();
        
        if($charObj != null) {
            $char = $charObj;
            return view('character.show', compact('char'));
        } 
        else {
            echo "not found";
        }

    }
}
