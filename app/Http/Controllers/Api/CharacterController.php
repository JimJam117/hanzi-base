<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Libraries\Radicals;
use Transliterator;

class CharacterController extends Controller
{

    public function sortBy() {

    }


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
        return compact('ccdb');
    }









    /**
     * Returns all of the characters, paginated
     * 
     * @return All characters
     */
    public function index($sortBy, $Hfilter = "all", $Cfilter = "all", $Rfilter = false)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 30;

        $newChars = [];

        // set the order to either asc or desc
        $sortOrder = 'asc';
        $sortBy == "updated_at" ? $sortOrder = 'desc' : null;
        $sortBy == "pinyin" ? $sortBy = "pinyin_normalised" : null;
        $sortBy == 'heisig_number' ? $Hfilter = "yes" : null;
        
        if($Hfilter == "yes") {
            $chars = DB::table('characters')->where('heisig_number', '!=', null)->orderBy($sortBy, $sortOrder)->get();
        }
        else if ($Hfilter == "no"){
            $chars = DB::table('characters')->where('heisig_number', null)->orderBy($sortBy, $sortOrder)->get();
        }
        else{
            $chars = DB::table('characters')->orderBy($sortBy, $sortOrder)->get();
        }
        
        foreach ($chars as $char) {
            $hasSimplified = $char->simp_char ? true : false;
            $hasTraditional = $char->trad_char ? true : false;
            if ($hasTraditional) {
            $trads = $char->trad_char;
            $trads = explode(",", $trads);
            }
            // if has same trad as char, then does not have a traditional version
            foreach ($trads as $trad) {
                if ($trad == $char->char){
                    $hasTraditional = false;
                }
            }
            // if the char is the same as the simp_char, then does not have simplified version
            if($char->char == $char->simp_char) {
                $hasSimplified = false;
            }

            // if none of the conditions are true
            if(
                !(
                    ($Cfilter == "simp" && $hasSimplified) ||
                    ($Cfilter == "trad" && $hasTraditional) ||
                    ($Rfilter == "true" && ($char->char != $char->radical))
                )
            )
            {
                array_push($newChars, ['char' => $char, 'hasSimplified' => $hasSimplified, 'hasTraditional' => $hasTraditional]);
            }            
        }

        $results = collect($newChars);
        
        $chars = new LengthAwarePaginator($results->forPage($currentPage, $perPage), $results->count(), $perPage, $currentPage, ['path' => "/index"]);
        return (compact('chars'));
    }



    /**
     * Show the radical search
     * 
     * @param $search The search input
     * @return radical search 
     */
    public function showRadicalSearch ($search = null, $sortBy, $Hfilter = "all", $Cfilter = "all") {
        
        // check if the input is in either radical array
        $isInArray = array_search($search, Radicals::returnArray());
        $isInSimpArray = array_search($search, Radicals::returnSimplifedArray());

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 30;

        $newChars = [];

        // set the order to either asc or desc
        $sortOrder = 'asc';
        $sortBy == "updated_at" ? $sortOrder = 'desc' : null;
        $sortBy == "pinyin" ? $sortBy = "pinyin_normalised" : null;
        $sortBy == 'heisig_number' ? $Hfilter = "yes" : null;


        if($Hfilter == "yes") {
            $chars = \App\Character::where('heisig_number', '!=', null)
                        // closure for finding radical using $search
                        ->where(function($query) use ($search){
                            $query->where('radical', 'like', '%' . $search .'%')
                            ->orWhere('simp_radical', 'like', '%' . $search .'%');
                        })
                        ->orderBy($sortBy, $sortOrder)->get();
        }
        else if ($Hfilter == "no"){
            $chars = \App\Character::where('heisig_number', null)
                        // closure for finding radical using $search
                        ->where(function($query) use ($search){
                            $query->where('radical', 'like', '%' . $search .'%')
                            ->orWhere('simp_radical', 'like', '%' . $search .'%');
                        })
                        ->orderBy($sortBy, $sortOrder)->get();
        }
        else{
            $chars = \App\Character::where('radical', 'like', '%' . $search .'%')
                        ->orWhere('simp_radical', 'like', '%' . $search .'%')
                        ->orderBy($sortBy, $sortOrder)->get();
        }


        foreach ($chars as $char) {
            $hasSimplified = $char->simp_char ? true : false;
            $hasTraditional = $char->trad_char ? true : false;
            if ($hasTraditional) {
            $trads = $char->trad_char;
            $trads = explode(",", $trads);
            }
            // if has same trad as char, then does not have a traditional version
            foreach ($trads as $trad) {
                if ($trad == $char->char){
                    $hasTraditional = false;
                }
            }
            // if the char is the same as the simp_char, then does not have simplified version
            if($char->char == $char->simp_char) {
                $hasSimplified = false;
            }

            // if none of the conditions are true
            if(
                !(
                    ($Cfilter == "simp" && $hasSimplified) ||
                    ($Cfilter == "trad" && $hasTraditional)
                )
            )
            {
                array_push($newChars, ['char' => $char, 'hasSimplified' => $hasSimplified, 'hasTraditional' => $hasTraditional]);
            }            
        }
      

        $results = collect($newChars);
        
        $chars = new LengthAwarePaginator($results->forPage($currentPage, $perPage), $results->count(), $perPage, $currentPage, ['path' => "/radical/search/$search"]);
        return (compact('search', 'chars'));
    }

    
    private function charSearch($inputArray = []) {
        $results = [];
        $charResults = [];

        // character results, items that match a character exactly
        foreach ($inputArray as $inputItem) {

            $charResults = \App\Character::where('char', $inputItem)->get();

            // for each result in the above collections, add to results array

            foreach($charResults as $result) {
                if (! in_array($result, $results)) {
                    array_unshift($results, $result);
                }
            }
        }

        return $results;
    }


    /**
     * Returns a staggered set of search results
     * 
     * Provided an array of items to search through, this will return a staggered
     * search which will order the search in groups. The groups are as follows:
     *  # Exact character matches,
     *  # Exact pinyin matches,
     *  # Fuzzy pinyin matches (using the 'like' operator),
     *  # Fuzzy translation and Heisig matches
     * 
     * @param String $inputArray The array of strings to search for
     * @return Array $results The search results
     * 
     */
    private function staggeredSearch($inputArray = []) {
        $results = [];
        $exactMatchesResults = [];
        $pinyinResults = [];
        $translationResults = [];
        

        // Exact pinyin results, items that match exactly
        foreach ($inputArray as $inputItem) {

            $exactMatchesResults = \App\Character::where('pinyin', $inputItem)
            ->orWhere('pinyin_normalised', $inputItem)->orderBy('freq', 'asc')->get();

            // for each result in the above collections, add to results array

            foreach($exactMatchesResults as $result) {
                if (! in_array($result, $results)) {
                    array_push($results, $result);
                }
            }
        }

        // pinyin results using "like" operator
        foreach ($inputArray as $inputItem) {
    
            $pinyinResults = \App\Character::where('pinyin', 'like', '%' . $inputItem .'%')
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

        return $results;
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
    public function fetchSearchResults($input, $sortBy = "default", $Hfilter = "all", $Cfilter = "all", $Rfilter = false) {
        
        // find if there are any hanzi in the string
        $hanzi = [];
        preg_match_all("/\p{Han}/u", $input, $hanzi);

        // find all the words within the input string
        $inputArray = [];
        preg_match_all("/[A-Za-z0-9]+/u", $input, $inputArray);
        
        // an extra layer of arrays is created, removing them here
        $inputArray = $inputArray[0];

        $atLeastOneNonHanzi = sizeof($inputArray) > 0;

        $hanzi = $hanzi[0];
        
        // reverse the hanzi array it's in the order the user provided
        array_reverse($hanzi);

        // add all the hanzi matches to the inputArray
        foreach ($hanzi as $char) {
            array_unshift($inputArray, $char);
        }

        //dd($inputArray);

        // these settings are for the custom LengthAwarePaginator
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 30;

        // set the order to either asc or desc
        $sortOrder = 'asc';
        $sortBy == "updated_at" ? $sortOrder = 'desc' : null;
        $sortBy == "pinyin" ? $sortBy = "pinyin_normalised" : null;
        $sortBy == 'heisig_number' ? $Hfilter = "yes" : null;

        $newChars = [];

        // the search results, determined by the sortBy
        if ($sortBy == 'default') {
            $results = [];
            $charResults = $this->charSearch($inputArray);
            $otherResults = $this->staggeredSearch($inputArray);
            
            foreach($charResults as $result) {
                if (! in_array($result, $results)) {
                    array_push($results, $result);
                }
            }
            foreach($otherResults as $result) {
                if (! in_array($result, $results)) {
                    array_push($results, $result);
                }
            }
            
            
        }
        
        // is not the default sorting
        else {
            $sortBy == "pinyin" ? $sortBy = "pinyin_normalised" : null; 

            // set the order to either asc or desc
            $sortOrder = 'asc';
            $sortBy == "updated_at" ? $sortOrder = 'desc' : null;


            $results = [];
            $otherResults = [];
            
            // if there is at least one word in the query that's not hanzi
            if($atLeastOneNonHanzi) {

                // charResults
                $charResults = $this->charSearch($inputArray);
                foreach($charResults as $result) {
                    if (! in_array($result, $results)) {
                        array_push($results, $result);
                    }
                }
                
                // other matches
                foreach ($inputArray as $inputItem) {

                    $otherResultsTemp = \App\Character::where('heisig_keyword', 'like', '%' . $inputItem .'%')
                            ->orWhere('pinyin', 'like', '%' . $inputItem .'%')
                            ->orWhere('pinyin_normalised', 'like', '%' . $inputItem .'%')
                            ->orWhere('translations', 'like', '%' . $inputItem .'%')
                            ->orWhere('heisig_number', 'like', '%' . $inputItem .'%')
                            ->orderBy($sortBy, $sortOrder)->get();

                    // for each result in the above collections, add to results array

                    foreach($otherResultsTemp as $result) {
                        if (! in_array($result, $otherResults)) {
                            // if the sortBy is heisig, then remove all results that don't have heisig data
                            if (!($sortBy == "heisig_number" && $result->heisig_number == null)) {
                                array_push($otherResults, $result);
                            }
                        }
                    }
                }

                // collect the other results and sort
                $otherResults = collect($otherResults);
                if($sortOrder == "desc"){
                    $otherResults = $otherResults->sortByDesc($sortBy)->values()->all();
                }
                else {
                    $otherResults = $otherResults->sortBy($sortBy)->values()->all();
                }
                

                foreach($otherResults as $result) {
                    if (! in_array($result, $results)) {
                        array_push($results, $result);
                    }
                }

                
            }

            // if all the items in the inputArray are hanzi
            else {
                $results = $this->charSearch($inputArray);
                $results = collect($results);

                // sort by the sortBy value, then reset the values for the keys and collect results again
                $results = $results->sortBy($sortBy)->values()->all();
                
            }
        }

        
        foreach ($results as $char) {
            $hasSimplified = $char->simp_char ? true : false;
            $hasTraditional = $char->trad_char ? true : false;
            if ($hasTraditional) {
            $trads = $char->trad_char;
            $trads = explode(",", $trads);
            }
            // if has same trad as char, then does not have a traditional version
            foreach ($trads as $trad) {
                if ($trad == $char->char){
                    $hasTraditional = false;
                }
            }
            // if the char is the same as the simp_char, then does not have simplified version
            if($char->char == $char->simp_char) {
                $hasSimplified = false;
            }

            // if none of the conditions are true
            if(
                !(
                    ($Cfilter == "simp" && $hasSimplified) ||
                    ($Cfilter == "trad" && $hasTraditional) ||
                    ($Rfilter == "true" && ($char->char != $char->radical)) || 
                    ($Hfilter == "no" && $char->heisig_number != null) ||
                    ($Hfilter == "yes" && $char->heisig_number == null)
                )
            )
            {
                array_push($newChars, ['char' => $char, 'hasSimplified' => $hasSimplified, 'hasTraditional' => $hasTraditional]);
            }            
        }
        
        // collect the results
        $newChars = collect($newChars);

        // return the results array as a paginatior
        $chars = new LengthAwarePaginator($newChars->forPage($currentPage, $perPage), $newChars->count(), $perPage, $currentPage, ['path' => "/search/$input"]);
       
        $search = $input;
        return (compact('search', 'chars', 'hanzi'));
    }
}
