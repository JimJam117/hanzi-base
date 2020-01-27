<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterController extends Controller
{
    public function index()
    {
        // DEBUGGING
        $chars = \App\Character::all();
        $chars = DB::table('characters')->paginate(15);

        $ccdb = $this->ccdb('們'); 


        return view('character.index', compact(['chars', 'ccdb']));
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

    public function ccdb($char) {

        $ccdb = json_decode(file_get_contents("http://ccdb.hemiola.com/characters/string/" . $char . "?fields=kDefinition,kFrequency,kTotalStrokes,kSimplifiedVariant,kTraditionalVariant"), true);
        $ccdb = $ccdb[0];
        // add the orignal to the output
        $ccdb += ['original' => $char];

        // grab the trad and simp chars
        $raw_trad = $this->grabUnicodeChar($ccdb['kTraditionalVariant']);
        $raw_simp = $this->grabUnicodeChar($ccdb['kSimplifiedVariant']);
        
        // add the trad and simp chars
        $ccdb += ['traditional_actual' => $raw_trad];
        $ccdb += ['simplified_actual' => $raw_simp];

        return $ccdb;
    }

    function grabUnicodeChar($string) {
        
        $trimmed = trim($string, "U+");
        $unicodeChar = "\u$trimmed";
        return json_decode('"'.$unicodeChar.'"');
    }
}
