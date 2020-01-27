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

        $ccdb = $this->grabCharacterData('的'); 


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

    public function grabCharacterData($char) {
        
        // glosbe pinyin
        $char_encoded = urlencode($char);
        $glosbe = json_decode(file_get_contents("https://glosbe.com/transliteration/api?from=Han&dest=Latin&text=". "$char_encoded" ."&format=json"), true);
        $pinyin = $glosbe['text'];

        // grab the data from ccdb
        $ccdb = json_decode(file_get_contents("http://ccdb.hemiola.com/characters/string/" . $char . "?fields=kDefinition,kFrequency,kTotalStrokes,kSimplifiedVariant,kTraditionalVariant"), true);
        $ccdb = $ccdb[0];
        // add the orignal to the output
        $ccdb += ['original' => $char];

        // grab the trad and simp chars
        $raw_trad = $this->grabUnicodeChar($ccdb['kTraditionalVariant']) ?? $char;
        $raw_simp = $this->grabUnicodeChar($ccdb['kSimplifiedVariant']) ?? $char;
        
        // add the trad and simp chars
        $ccdb += ['traditional_actual' => $raw_trad];
        $ccdb += ['simplified_actual' => $raw_simp];

        // add glosbe pinyin
        $ccdb += ['pinyin' => $pinyin];
        return $ccdb;
    }

    function grabUnicodeChar($string) {
        
        $trimmed = trim($string, "U+");
        $unicodeChar = "\u$trimmed";
        return json_decode('"'.$unicodeChar.'"');
    }
}
