<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/grabCharacterData/{char}', 'Api\CharacterController@grabCharacterData');
Route::get('/chars/index/sortBy/{sortBy}/{Hfilter}/{Cfilter}/{Rfilter}', 'Api\CharacterController@index');
Route::get('/search/{input}/sortBy/{sortBy}/{Hfilter}/{Cfilter}/{Rfilter}', 'Api\CharacterController@fetchSearchResults')->name('search');
Route::get('/radical/search/{search}/sortBy/{sortBy}/{Hfilter}/{Cfilter}/{Rfilter}', 'Api\CharacterController@showRadicalSearch')->name('radicalSearch');

// Route::get('/chars/index', 'Api\CharacterController@index');
// Route::get('/search/{input}', 'Api\CharacterController@fetchSearchResults')->name('search');
// Route::get('/radical/search/{search}', 'Api\CharacterController@showRadicalSearch')->name('radicalSearch');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
