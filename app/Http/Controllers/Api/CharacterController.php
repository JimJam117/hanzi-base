<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Libraries\Radicals;

class CharacterController extends Controller
{

    public function sortBy() {

    }

    /**
     * Returns all of the characters, paginated
     * 
     * @return All characters
     */
    public function index($sortBy)
    {
        if($sortBy === 'pinyin') {
            
            $chars = DB::table('characters')->orderBy('pinyin_normalised', 'desc')->paginate(30);
        }
        else{
            $chars = DB::table('characters')->paginate(30);
        }
        
        return (compact('chars'));
    }



        /**
     * Show the radical search
     * 
     * @param $search The search input
     * @return radical search 
     */
    public function showRadicalSearch ($search = null, $sortBy) {
        
        // check if the input is in either radical array
        $isInArray = array_search($search, Radicals::returnArray());
        $isInSimpArray = array_search($search, Radicals::returnSimplifedArray());


        // if input is valid, return the view

        if($sortBy === 'pinyin') {
            $chars = \App\Character::where('radical', 'like', '%' . $search .'%')
                        ->orWhere('simp_radical', 'like', '%' . $search .'%')->orderBy('pinyin_normalised', 'desc')->paginate(30);
        }
        else{
            $chars = \App\Character::where('radical', 'like', '%' . $search .'%')
                        ->orWhere('simp_radical', 'like', '%' . $search .'%')->paginate(30);
        }

            
            
            



            return (compact('search', 'chars'));
        
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
        $charResults = [];
        $exactMatchesResults = [];
        $pinyinResults = [];
        $translationResults = [];
        

        // character results, items that match a character exactly
        foreach ($inputArray as $inputItem) {

            $charResults = \App\Character::where('char', $inputItem)->get();

            // for each result in the above collections, add to results array

            foreach($charResults as $result) {
                if (! in_array($result, $results)) {
                    array_push($results, $result);
                }
            }
        }

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
    public function fetchSearchResults($input, $sortBy = "default") {
        
        // find if there are any hanzi in the string
        $hanzi = [];
        preg_match_all("/\p{Han}/u", $input, $hanzi);

        // find all the words within the input string
        $inputArray = [];
        preg_match_all("/[A-Za-z]+/u", $input, $inputArray);
        
        // an extra layer of arrays is created, removing them here
        $inputArray = $inputArray[0];
        $hanzi = $hanzi[0];
        
        // reverse the hanzi array it's in the order the user provided
        array_reverse($hanzi);

        // add all the hanzi matches to the inputArray
        foreach ($hanzi as $char) {
            array_unshift($inputArray, $char);
        }



        // these settings are for the custom LengthAwarePaginator
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 30;

        // the search results, determined by the sortBy
        if ($sortBy == 'default') {
            $results = $this->staggeredSearch($inputArray);
        }
        if ($sortBy == 'pinyin') {
            $results = [];
            $charResults = [];
            $otherResults = [];
        

        // character results, items that match a character exactly
        foreach ($inputArray as $inputItem) {

            $charResults = \App\Character::where('char', $inputItem)->get();

            // for each result in the above collections, add to results array

            foreach($charResults as $result) {
                if (! in_array($result, $results)) {
                    array_push($results, $result);
                }
            }
        }

        // other matches
        foreach ($inputArray as $inputItem) {

            $otherResults = \App\Character::where('heisig_keyword', 'like', '%' . $inputItem .'%')
                    ->orWhere('pinyin', 'like', '%' . $inputItem .'%')
                    ->orWhere('pinyin_normalised', 'like', '%' . $inputItem .'%')
                    ->orWhere('translations', 'like', '%' . $inputItem .'%')
                    ->orWhere('heisig_number', 'like', '%' . $inputItem .'%')->orderBy('pinyin_normalised', 'asc')->get();

            // for each result in the above collections, add to results array

            foreach($otherResults as $result) {
                if (! in_array($result, $results)) {
                    array_push($results, $result);
                }
            }
        }
        }
        
        // return the results array as a paginatior
        $results = collect($results);

        $chars = new LengthAwarePaginator($results->forPage($currentPage, $perPage), $results->count(), $perPage, $currentPage, ['path' => "/search/$input"]);
       
        $search = $input;
        return (compact('search', 'chars'));
    }
}
