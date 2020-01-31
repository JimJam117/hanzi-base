<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Transliterator;

class CharacterController extends Controller
{
    public function notfound() {
        return view('character.notfound');
    }

    public function index()
    {
        
        // DEBUGGING
        $chars = \App\Character::all();
        $chars = DB::table('characters')->paginate(15);

        //$ccdb = $this->grabCharacterData('台'); 

        return view('character.index', compact(['chars']));
    }

    public function show($char) {
        $newCharAdded = false;

        // find the character
        $charObj = null;
        $charObj = \App\Character::where('char', $char)->orWhere('id', $char)->first();
        
    
        //dd($this->grabCharacterData($char));
        if($charObj == null) {

            $charData = $this->grabCharacterData($char);
            if($charData != null) {
                $this->addToDatabase($charData);
                $charObj = \App\Character::where('char', $char)->orWhere('id', $char)->first();
                $newCharAdded = true;
            }
            else{
                return view('character.notfound', compact('char'));
            }

            
        }
        /*
        // if could not find the simplified char
        if($charObj == null) {
        $charObj = \App\Character::where('trad_char', 'LIKE', "%{$char}%")->firstOrFail();

        //
        //->orWhere('freq', $char) 
        //->orWhere('keyword', $char)->firstOrFail();

            
        } 
        */
        
        
        
        // used to determine which frequency string should be used
        switch ($charObj->freq) {
            case 1:
                $charObj->frequencyTitle = "Very Common";
                break;

            case 2:
                $charObj->frequencyTitle = "Common";
                break;

            case 3:
                $charObj->frequencyTitle = "Frequent";
                break;

            case 4:
                $charObj->frequencyTitle = "Infrequent";
                break;

            case 5:
                $charObj->frequencyTitle = "Very Infrequent";
                 break;
            
            default:
                $charObj->frequencyTitle = "Unknown";
                break;
        }

        $char = $charObj;

        return view('character.show', compact(['char','newCharAdded']));

    }

    public function debug($char) {
        dd($this->grabCharacterData($char));
    }

    public function grabCharacterData($char) {
        // make sure only one character is used
        $char = mb_substr($char, 0, 1);

        // grab the data from ccdb
        $ccdb = json_decode(file_get_contents("http://ccdb.hemiola.com/characters/string/" . $char . "?fields=kDefinition,kFrequency,kTotalStrokes,kSimplifiedVariant,kTraditionalVariant"), true);
        if (empty($ccdb)) {
            return null;
        }
        $ccdb = $ccdb[0];
        // add the orignal to the output
        $ccdb += ['original' => $char];

        // grab the trad and simp chars
        $raw_trads = explode ( " " , $ccdb['kTraditionalVariant']);
        $raw_trad = "";
        $i = 0;
        if (!empty($raw_trads)){
            foreach ($raw_trads as $trad) {
                if($i != 0) {
                    $raw_trad = $raw_trad . ",";
                }
                else{
                    $i = 1;
                }
                $raw_trad = $raw_trad . $this->grabUnicodeChar($trad);
            }
        }
        
        if(empty($raw_trad)) {
            $raw_trad = $char;
        }
        

        $raw_simp = $this->grabUnicodeChar($ccdb['kSimplifiedVariant']) ?? $char;
        
        // add the trad and simp chars
        $ccdb += ['traditional_actual' => $raw_trad];
        $ccdb += ['simplified_actual' => $raw_simp];

        // glosbe pinyin
        $char_encoded = urlencode($char);
        $glosbe = json_decode(file_get_contents("https://glosbe.com/transliteration/api?from=Han&dest=Latin&text=". "$char_encoded" ."&format=json"), true);
        $pinyin = $glosbe['text'];

        $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;', Transliterator::FORWARD);
        $pinyin_normalised = $transliterator->transliterate($pinyin);

        // add glosbe pinyin
        $ccdb += ['pinyin' => $pinyin];
        $ccdb += ['pinyin_normalised' => $pinyin_normalised];
        return $ccdb;
    }

    function grabUnicodeChar($string) {
        
        $trimmed = trim($string, "U+");
        $unicodeChar = "\u$trimmed";
        return json_decode('"'.$unicodeChar.'"');
    }

    function addToDatabase($data) {

        //dd($data);
        \App\Character::create([
        'char'                      => $data['original'],
        'simp_char'                 => $data['simplified_actual'],
        'trad_char'                 => $data['traditional_actual'],
        'freq'                      => $data['kFrequency'],
        'pinyin'                    => $data['pinyin'],
        'pinyin_normalised'   => $data['pinyin_normalised'],
        'translations'              => $data['kDefinition'],
        ]);

    }
}
