<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Transliterator;

class SearchController extends Controller
{
    public function fetch() {
        $query = request()->input('query');

        return redirect("/search/" . $query);
    }

    public function show($search = null) {
        if ($search == null) {
            return redirect('/');
        }

        
        $results = \App\Character::where('char', 'LIKE', "%{$search}%")
                                    ->orWhere('pinyin', 'LIKE', "%{$search}%")
                                    ->orWhere('pinyin_normalised', 'LIKE', "%{$search}%")
                                    ->orWhere('heisig_keyword', 'LIKE', "%{$search}%")
                                    ->orWhere('translations', 'LIKE', "%{$search}%")
                                    ->orWhere('heisig_number', 'LIKE', "%{$search}%")
                                    ->orWhere('id', 'LIKE', "%{$search}%")
                                    ->paginate(15);
       

        return view('character.search', compact('search', 'results'));
    }
}
