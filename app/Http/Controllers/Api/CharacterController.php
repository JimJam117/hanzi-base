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
     * Fetches search results from a string
     * 
     * This function is used for if the query contains hanzi
     * 
     * @param String $input The search query
     * @return LengthAwarePaginator $results The results for the array
     * 
     */
    public function fetchHanziSearchResults($input, $sortBy) {
            $searchExploded = mb_str_split($input);

            $resultArray = [];

            // foreach character in search exploded where the character is a hanzi, add to the resultArray
            foreach ($searchExploded as $searchCharacter) {
                preg_match("/\p{Han}+/u", $searchCharacter) ? array_push($resultArray, $searchCharacter) : null ;
            }


            // use the $resultArray to find the characters in the DB
            $chars = \App\Character::where(function ($query) use($resultArray) {

                // find the chars
                foreach ($resultArray as $resultItem) {
                    $query->orwhere('char', 'like',  '%' . $resultItem .'%');
                }      
            })->paginate(30);

            $search = $input;
            return (compact('search', 'chars'));
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
    public function fetchSearchResults($input, $sortBy) {
        
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

        $chars = new LengthAwarePaginator($results->forPage($currentPage, $perPage), $results->count(), $perPage, $currentPage, ['path' => "/search/$input"]);
       
        $search = $input;
        return (compact('search', 'chars'));
    }
}
