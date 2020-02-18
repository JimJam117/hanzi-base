<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Transliterator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Libraries\Radicals;

class CharacterController extends Controller
{

    /**
     * Takes a string of JSON data and converts it into characters, then adds those characters to db
     * 
     * The JSON should be formatted as follows:
     * [
     *  {
     *      "Character":"一",
     *      "Number":"1",
     *      "Keyword":"One"
     *  },
     *  {
     *      "Character":"二",
     *      "Number":"2",
     *      "Keyword":"Two"
     *  },
     *  etc...
     * ]
     * 
     * @param string $heisigCharsJson The JSON string
     * @return void
     */
    public function addHeisigCharacters($heisigCharsJson = null) {

        // used to manually set the string
        // $heisigCharsJson = '';

        //decode the characters into an array
        $heisigChars = json_decode($heisigCharsJson, TRUE);
 

        foreach ($heisigChars as $data) {

            // get the character data passing in the heisig properties
            $charData = $this->grabCharacterData(
                $data['Character'], // Character
                $data['Number'], // Heisig Number 
                $data['Keyword'] // Heisig Keyword
            );
            
            // add to db 
            $this->addToDatabase($charData);
        }
    }








    public function notfound() {
        return view('character.notfound');
    }

    public function index()
    {
        $chars = DB::table('characters')->paginate(30);
        return view('character.index', compact(['chars']));
    }

    public function addTheHeisig() {

    }


    /**
     * Return the correct page for the single character given.
     * 
     * @param string $char The character to show
     * @return view
     */
    public function showSingle($char) {
        // used if the character added is new
        $newCharAdded = false;

        // check if there are multiple characters
        if ( mb_strlen($char) > 1 ) { return view('character.notfound', compact('char')); }
  
        // find the character
        $characterObj = \App\Character::where('char', $char)->orWhere('id', $char)->first();
             
        // if the character could not be found
        if(! isset($characterObj->id)) {

            // check if the character is within the database
            $data = $this->grabCharacterData($char);
            if ($data == null) { return view('character.notfound', compact('char')); }
            
            // if the character exists, add it to the database
            $this->addToDatabase($data);
            $characterObj = \App\Character::where('char', $char)->orWhere('id', $char)->first();
            $newCharAdded = true;  
        }

        // set the frequency title
        switch ($characterObj->freq) {
            case 1: $characterObj->frequencyTitle =  "Very Common"; break;
            case 2: $characterObj->frequencyTitle =  "Common"; break;
            case 3: $characterObj->frequencyTitle =  "Frequent"; break;
            case 4: $characterObj->frequencyTitle =  "Infrequent"; break;
            case 5: $characterObj->frequencyTitle =  "Very Infrequent"; break;
            default: $characterObj->frequencyTitle =  "Unknown"; break;
        }

        $char = $characterObj;
        return view('character.show', compact('char', 'newCharAdded'));
    }


    public function debug($char) {
        dd($this->grabCharacterData($char));
    }

    public function grabCharacterData($char, $heisig_number = null, $heisig_keyword = null) {
        // make sure only one character is used
        $char = mb_substr($char, 0, 1);
        
        // grab the data from ccdb
        $ccdb = json_decode(file_get_contents("http://ccdb.hemiola.com/characters/string/" . $char . "?fields=kDefinition,kFrequency,kTotalStrokes,kSimplifiedVariant,kTraditionalVariant,kRSUnicode"), true);
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

        // radicals

        // grab the radical number from the kRSUnicode, which is in the format 'radical-number','extra-strokes'
        // only the radical number is needed, so explode then grab first element
        $radicalNumber = explode(".", $ccdb['kRSUnicode']);

        // also remove any "'" that's in the first array item
        $radicalNumber = explode("'", $radicalNumber[0]);
        $radicalNumber = $radicalNumber[0];



        //radical fetch from Radicals class
        $radical = Radicals::returnRadical($radicalNumber);
        $simp_radical = Radicals::returnSimplifedRadical($radicalNumber) ?? $radical;
        

        // add the radicals
        $ccdb += ['radical' => $radical];
        $ccdb += ['simplified_radical' => $simp_radical];

        // glosbe pinyin
        $char_encoded = urlencode($char);
        $glosbe = json_decode(file_get_contents("https://glosbe.com/transliteration/api?from=Han&dest=Latin&text=". "$char_encoded" ."&format=json"), true);
        $pinyin = $glosbe['text'];

        $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;', Transliterator::FORWARD);
        $pinyin_normalised = $transliterator->transliterate($pinyin);

        // add glosbe pinyin
        $ccdb += ['pinyin' => $pinyin];
        $ccdb += ['pinyin_normalised' => $pinyin_normalised];

        // add the heisig data
        $ccdb += ['heisig_number' => $heisig_number];
        $ccdb += ['heisig_keyword' => $heisig_keyword];

        // return the character obj
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
        'stroke_count'              => $data['kTotalStrokes'],
        'radical'                   => $data['radical'],
        'simp_radical'              => $data['simplified_radical'],
        'pinyin'                    => $data['pinyin'],
        'pinyin_normalised'         => $data['pinyin_normalised'],
        'translations'              => $data['kDefinition'],
        'heisig_keyword'            => $data['heisig_keyword'],
        'heisig_number'             => $data['heisig_number'],
        ]);

    }

    /**
     * Search Stuff
     * 
     */

    public function fetchSearch() {
        $query = request()->input('query');
        
        // if the search query is a radical search, add the radical to the search
        if (request()->input('radical')) {
            $radical = request()->input('radical');

            $search = request()->input('search');

            // get results for radical here after fish'n'chips
            $results = \App\Character::where('radical', 'like', '%' . $search .'%')
                                ->orWhere('simp_radical', 'like', '%' . $search .'%')->paginate(30);

            $isRadicalSearch = true;
            
            return view('character.search', compact('results', 'search', 'isRadicalSearch'));
        }

        return redirect("/search/" . $query);
    }

    /**
     * Used for fetch results from an array of strings
     * 
     */
    public function fetchArraySearchResults($inputArray) {

        // incase $inputArray is a string, convert it to an array
        if (is_string($inputArray)) { $inputArray = array($inputArray); }

        // these settings are for the custom LengthAwarePaginator
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 30;

        $results = [];
        $pinyinResults = [];
        $translationResults = [];

        foreach ($inputArray as $inputItem) {
            // actual results
            $pinyinResults = \App\Character::where('char', $inputItem)
            ->orWhere('pinyin', $inputItem)
            ->orWhere('radical', $inputItem)
            ->orWhere('pinyin_normalised', $inputItem)->orderBy('freq', 'asc')->get();

            // for each result in the above collections, add to results array

            foreach($pinyinResults as $result) {
                if (! in_array($result, $results)) {
                    array_push($results, $result);
                }
            }
        }

        foreach ($inputArray as $inputItem) {
            // pinyin and char results
            $pinyinResults = \App\Character::where('char', 'like', '%' . $inputItem .'%')
            ->orWhere('pinyin', 'like', '%' . $inputItem .'%')
            ->orWhere('radical', 'like', '%' . $inputItem .'%')
            ->orWhere('pinyin_normalised', 'like', '%' . $inputItem .'%')->orderBy('freq', 'asc')->get();

            // for each result in the above collections, add to results array

            foreach($pinyinResults as $result) {
                if (! in_array($result, $results)) {
                    array_push($results, $result);
                }
            }
        }

        foreach ($inputArray as $inputItem) {

            // translation and heisig results
            $translationResults = \App\Character::where('heisig_keyword', 'like', '%' . $inputItem .'%')
                    ->orWhere('translations', 'like', '%' . $inputItem .'%')
                    ->orWhere('heisig_number', 'like', '%' . $inputItem .'%')->get();

            // for each result in the above collections, add to results array
            foreach($translationResults as $result) {
                if (! in_array($result, $results)) {
                    array_push($results, $result);
                }
            }
        }
        
        // return the results array as a paginatior
        $results = new LengthAwarePaginator($results, count($results), $perPage, $currentPage);
        return $results;
    }

    /**
     * 
     * 
     */
    public function showSearch($search = null) {

        $resultArray = [];
        $newCharArray = [];

        $search = (urldecode($search));

        // if the search is only 1 character long
        if (mb_strlen($search) == 1) {
            $results = $this->fetchArraySearchResults($search);

            // if there were no results, do a check to see if it is a valid single character
            if($results->total() <= 1) {
                
                // if it is, then send to character page (where a new character will be generated)
                $charData = $this->grabCharacterData($search);
                if($charData != null) { return redirect('/character/' . $search); }  
            }

            // return the results
            return view('character.search', compact('search', 'results'));
        }

        // there are no chinese charcters in it
        if (! preg_match("/\p{Han}+/u", $search)) {
            $searchExploded = explode(" ", $search);
            $results = $this->fetchArraySearchResults($searchExploded);
            
            // return the results
            return view('character.search', compact('search', 'results'));  
        }

        // if the search contains chinese characters
        if(preg_match("/\p{Han}+/u", $search) ) {
            $searchExploded = mb_str_split($search);

            $charsArray = [];

            // foreach character in search exploded where the character is a hanzi, add to the charsArray
            foreach ($searchExploded as $searchCharacter) {
                preg_match("/\p{Han}+/u", $searchCharacter) ? array_push($charsArray, $searchCharacter) : null ;
            }
            
            $explodedSearchFuncResult = $this->explodedSearchDatabaseAddHandler($charsArray);

            $resultArray = $explodedSearchFuncResult[0];
            $newCharArray = $explodedSearchFuncResult[1];

            
            $results = \App\Character::where(function ($query) use($resultArray) {

                // find the chars
                foreach ($resultArray as $resultItem) {
                    $query->orwhere('char', 'like',  '%' . $resultItem .'%');
                }      
            })->paginate(30);
        }
        
        if($newCharArray) {
            return view('character.search', compact('search', 'results', 'newCharArray'));
        }
        else {
            return view('character.search', compact('search', 'results'));
        }        
    }

    /**
     * This function is used to add new characters to the database
     * 
     */
    function explodedSearchDatabaseAddHandler($inputArray) {
        // new array for chars that are new 
        $newArray = [];

        // foreach item in that array
        foreach ($inputArray as $item) {
            
            // if its not already in the database
            if (! \App\Character::where('char', $item)->first()) {
                $charData = $this->grabCharacterData($item);
                
                if($charData != null) { 
                    
                    $this->addToDatabase($charData); 
                   
                    array_push($newArray, $charData);
                }
            }
            
        }
        return array($inputArray, $newArray);
    }

}
