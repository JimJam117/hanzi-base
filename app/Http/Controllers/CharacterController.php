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
     * Converts the input character into a readable unicode character
     * 
     * @param String $input The input string 
     * @return String The character in the correct unicode format
     */
    function grabUnicodeChar($input) {
        
        $trimmed = trim($input, "U+");
        $unicodeChar = "\u$trimmed";
        return json_decode('"'.$unicodeChar.'"');
    }



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
    
        //decode the characters into an array
        $heisigChars = json_decode($heisigCharsJson, TRUE);

        foreach ($HeisigArray as $data) {

            //dd("was");
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


    /**
     * Return a blank character notfound page
     * 
     * @return view Blank notfound page
     */
    public function notfound() {
        $char = null;
        return view('character.notfound', compact('char'));
    }


    /**
     * Returns all of the characters, paginated
     * 
     * @return view All characters view
     */
    public function index()
    {
        $chars = DB::table('characters')->paginate(30);
        return view('character.index', compact(['chars']));
    }



    /**
     * Grabs the infomation for a given character from the APIs
     * 
     * Uses the ccdb api, along with glosbe for pinyin and the radicals library for getting radicals to return a $character object ready to be added to the database
     * 
     * @param String $char The target character 
     * @param String $heisig_number The heisig number to be provided if available, optional
     * @param String $heisig_keyword The heisig keyword to be provided if available, optional
     * 
     */

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

        
        // ====== radicals ====== //

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

        // if the kFrequency is null, set it to 6 instead
        // (this is to make it less of a priority when searching)
        if ($ccdb['kFrequency'] == null) {
            $ccdb['kFrequency'] = 6;
        }

        // return the character obj
        return $ccdb;
    }


    /**
     * Takes the data and creates a character in the database
     * 
     * @param Array the data to create the char with
     * @return Void
     */
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


    



    /*
     *  ====================
     *      SEARCH
     *  ====================
     */


    /**
     * Checks if the chars in the input array are in the DB, adds them if not and returns list of added chars
     * 
     * @param Array $inputArray Array of characters
     * @return Array Returns an array consisting of the new Characters
     */
    function addArrayToDatabase($inputArray) {
        // for chars that are new to HanziBase
        $newArray = [];

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
        return $newArray;
    }


    public function fetchSearch() {
      
        // if the search query is a radical search, add the radical to the search
        if (request()->input('radical')) {
            return redirect("/radical/search/" . request()->input('search'));
        }

        return redirect("/search/" . request()->input('query'));
    }

    /**
     * Fetches search results from a string
     * 
     * THis function is used mainly for pinyin and translations.
     * 
     * @param String $input The search query
     * @return LengthAwarePaginator $results The results for the array
     * 
     */
    public function fetchSearchResults($input) {
        
        $inputArray = explode(" ", $input);

        // these settings are for the custom LengthAwarePaginator
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 30;

        $results = [];
        $pinyinResults = [];
        $translationResults = [];
        $actualResults = [];

        // Actual results, items that match exactly
        foreach ($inputArray as $inputItem) {

            $actualResults = \App\Character::where('char', $inputItem)
            ->orWhere('pinyin', $inputItem)
            ->orWhere('radical', $inputItem)
            ->orWhere('pinyin_normalised', $inputItem)->orderBy('freq', 'asc')->get();

            // for each result in the above collections, add to results array

            foreach($actualResults as $result) {
                if (! in_array($result, $results)) {
                    array_push($results, $result);
                }
            }
        }

        // pinyin and char results using "like" operator
        foreach ($inputArray as $inputItem) {
    
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

        // translation and heisig results using "like" operator
        foreach ($inputArray as $inputItem) {

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
        $results = collect($results);
        $results = new LengthAwarePaginator($results->forPage($currentPage, $perPage), $results->count(), $perPage, $currentPage, ['path' => "/search/$input"]);
        return $results;
    }

    /**
     * Show the search
     * 
     * @param $search The search input
     * @return view The search view
     */
    public function showSearch($search = null) {
        $resultArray = [];
        $newCharArray = [];

        $search = (urldecode($search));

        // if the search is only 1 character long
        if (mb_strlen($search) == 1) {
            $results = $this->fetchSearchResults($search);

            // if there were no results, do a check to see if it is a valid single character
            if($results->total() <= 1) {
                
                // if it is, then send to character page (where a new character will be generated)
                $charData = $this->grabCharacterData($search);
                if($charData != null) { return redirect('/character/' . $search); }  
            }
        }

        // if the search contains chinese characters
        else if (preg_match("/\p{Han}+/u", $search) ) {
            $searchExploded = mb_str_split($search);

            $resultArray = [];

            // foreach character in search exploded where the character is a hanzi, add to the resultArray
            foreach ($searchExploded as $searchCharacter) {
                preg_match("/\p{Han}+/u", $searchCharacter) ? array_push($resultArray, $searchCharacter) : null ;
            }
            
            $newCharArray = $this->addArrayToDatabase($resultArray);

            // use the $resultArray to find the characters in the DB
            $results = \App\Character::where(function ($query) use($resultArray) {

                // find the chars
                foreach ($resultArray as $resultItem) {
                    $query->orwhere('char', 'like',  '%' . $resultItem .'%');
                }      
            })->paginate(30);
        }

        // there are no chinese charcters in search
        else {
            $results = $this->fetchSearchResults($search);
        }
        
        // return view
        if($newCharArray) { return view('character.search', compact('search', 'results', 'newCharArray')); }
        else { return view('character.search', compact('search', 'results')); }        
    }


    /**
     * Show the radical search
     * 
     * @param $search The search input
     * @return view Either the radical search view or a character not found view
     */
    public function showRadicalSearch ($search = null) {
 
        // check if the input is in either radical array
        $isInArray = array_search($search, Radicals::returnArray());
        $isInSimpArray = array_search($search, Radicals::returnSimplifedArray());

        // if the search is not a radical
        if(!$isInArray && !$isInSimpArray) {
            $notRadical = true;
            $char = $search;
            return view('character.notfound', compact('char', 'notRadical'));
        }

        // if input is valid, return the view
        else{
            $results = \App\Character::where('radical', 'like', '%' . $search .'%')
                        ->orWhere('simp_radical', 'like', '%' . $search .'%')->paginate(30);

            return view('character.search', compact('search', 'results'));
        }
}
    

    /*
     * DEBUG
     * Returns debug infomation about a character via die and dump
     */
    public function debug($char) {
        dd($this->grabCharacterData($char));
    }
}
