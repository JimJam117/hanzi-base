<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterController extends Controller
{
    /**
     * Returns all of the characters, paginated
     * 
     * @return view All characters view
     */
    public function index()
    {
        
        $chars = DB::table('characters')->paginate(30);
        return (compact('chars'));
    }
}
