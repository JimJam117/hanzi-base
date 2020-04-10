<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Libraries\Radicals;

class CharacterController extends Controller
{
    /**
     * Returns all of the characters, paginated
     * 
     * @return All characters
     */
    public function index()
    {
        
        $chars = DB::table('characters')->paginate(30);
        return (compact('chars'));
    }



        /**
     * Show the radical search
     * 
     * @param $search The search input
     * @return radical search 
     */
    public function showRadicalSearch ($search = null) {
        
        // check if the input is in either radical array
        $isInArray = array_search($search, Radicals::returnArray());
        $isInSimpArray = array_search($search, Radicals::returnSimplifedArray());


        // if input is valid, return the view
        
            $chars = \App\Character::where('radical', 'like', '%' . $search .'%')
                        ->orWhere('simp_radical', 'like', '%' . $search .'%')->paginate(30);

            return (compact('search', 'chars'));
        
    }
}
