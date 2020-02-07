<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Transliterator;
use Illuminate\Pagination\LengthAwarePaginator;

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


    public function showHandler($char) {
         // check if there are multiple characters
         $moreThanOneChar = false;
         mb_strlen($char) > 1 ? $moreThanOneChar = true : $moreThanOneChar = false;

         
         if ($moreThanOneChar) {
            return view('character.notfound', compact('char'));
         }
         else {
            
            $showSingleData = $this->showSingle($char);
            
            $char = $showSingleData["char"];
            $newCharAdded = $showSingleData["newCharAdded"];
            return view('character.show', compact('char', 'newCharAdded'));
         }

    }

    function showSingle($char) {
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
        
        //return dd($char);
        return array('char' => $char, 'newCharAdded' => $newCharAdded);

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

    /**
     * Search Stuff
     * 
     */

    public function fetchSearch() {
        $query = request()->input('query');

        return redirect("/search/" . $query);
    }

    /**
     * 
     * 
     */
    public function showSearch($search = null) {

        $resultArray = [];

        // if the string is longer than 1 character
        if (mb_strlen($search) > 1) {
            $search = (urldecode($search));
            // check if there are any characters in the search string that match characters in the database
            $searchExploded = mb_str_split($search);

            // remove any spaces from the searchExploded Array
            $temp = [];
            foreach ($searchExploded as $searchCharacter){
                if ($searchCharacter != " " && $searchCharacter != "%20"){
                    array_push($temp, $searchCharacter);
                }
            }
            $searchExploded = $temp;
            
            // input check bools
            $input_characters_are_within_db = false;
            $input_characters_are_hanzi = false;

            foreach ($searchExploded as $value) {
                if (\App\Character::where('char', "$value")->first()) {
                    $input_characters_are_within_db = true;
                    break;
                    
                }
            }

            // if there are characters within the string that match hanzi in the database
            if ($input_characters_are_within_db) {
                //dd("multiple-chars-explosion-function; chars are in db");
                $resultArray = $this->explodedSearchDatabaseAddHandler($searchExploded);
                
            }

            // if not, check if they are hanzi using the ccdb
            else {
                foreach ($searchExploded as $value) {
                    if($value != "%20" && $value != " " && $value != "?") {
                        $charData = $this->grabCharacterData($value);
                        if ($charData != null) { 
                            $input_characters_are_hanzi = true; 
                            break; 
                        }
                    }
                    else{
                        if($value != " ") {
                        dd("Weird error: $value");
                        }
                    }
                    
                }

                // if true then a hanzi is within the search string, so the multiple-chars function will be run
                if ($input_characters_are_hanzi) {
                    //dd("multiple-chars-explosion-function; as there are chars that are hanzi (not in db)");
                    $resultArray = $this->explodedSearchDatabaseAddHandler($searchExploded);
                }

                // input niether within db or chinese char, run normal results without explsion or arrays, just as a string
                else {
                    //dd("single-string-function; no chinese char, run as normal string");
                    $resultArray = $search;
                }
            }

        }
        // if the string is only one character long
        else {
            //dd("only 1 char in string");
            $resultArray = $search;
        }
        

        
        // if the resultArray is a string
        if(is_string($resultArray)) {
            // these settings are for the custom LengthAwarePaginator
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 15;


            // pinyin and char results
            $pinyinResults = \App\Character::where('char', 'like', '%' . $resultArray .'%')
                                    ->orWhere('pinyin', 'like', '%' . $resultArray .'%')
                                    ->orWhere('pinyin_normalised', 'like', '%' . $resultArray .'%')->orderBy('freq', 'asc')->get();
            

            // translation and heisig results
            $translationResults = \App\Character::where('heisig_keyword', 'like', '%' . $resultArray .'%')
                                    ->orWhere('translations', 'like', '%' . $resultArray .'%')
                                    ->orWhere('heisig_number', 'like', '%' . $resultArray .'%')->get();

            // for each result in the above collections, add to results array
            $results = [];
            foreach($pinyinResults as $result) {
                array_push($results, $result);
            }
            foreach($translationResults as $result) {
                array_push($results, $result);
            }

            // return the results array as a paginatior
            $results = new LengthAwarePaginator($results, count($results), $perPage, $currentPage);

            
        }

        // if instead the resultArray is an array
        else{
            $results = \App\Character::where(function ($query) use($resultArray) {
                foreach ($resultArray as $letter) {
                    $query->orwhere('char', 'like',  '%' . $letter .'%');
                    $query->orwhere('pinyin', 'like',  '%' . $letter .'%');
                    $query->orwhere('pinyin_normalised', 'like',  '%' . $letter .'%');
                    $query->orwhere('heisig_keyword', 'like',  '%' . $letter .'%');
                    $query->orwhere('translations', 'like',  '%' . $letter .'%');
                    $query->orwhere('heisig_number', 'like',  '%' . $letter .'%');
                    $query->orwhere('id', 'like',  '%' . $letter .'%');
                }      
            })->paginate(15);
        }


        
        
        
        
        // if there were no results, do a check to see if it is a valid character
        
        if($results->total() <= 1) {
            
            // if it is, then send to character page (where a new character will be generated)
            $charData = $this->grabCharacterData($search);
            if($charData != null) {
                
                return redirect('/character/' . $search);
            }
            
           
        }
       
        // return the main results
        return view('character.search', compact('search', 'results'));
    }

    /**
     * This function is used to generate result arrays from strings containing multiple characters
     * 
     */
    function explodedSearchDatabaseAddHandler($searchExploded) {

        // foreach item in that array
        foreach ($searchExploded as $item) {
            
            // if its not already in the database
            if (! \App\Character::where('char', $item)->first()) {
                $charData = $this->grabCharacterData($item);
                
                if($charData != null) { $this->addToDatabase($charData); }
            }
            
        }
        return $searchExploded;
    }
}
