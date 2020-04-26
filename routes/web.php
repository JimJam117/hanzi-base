<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//history page
Route::get('/history', 'CharacterController@history');

// Search
Route::post('/search', 'CharacterController@fetchSearch');
Route::get('/search', 'CharacterController@goHome');
Route::get('/search/{search}', 'CharacterController@showSearch')->name('search');
Route::get('/radical/search/{search}', 'CharacterController@showRadicalSearch')->name('radicalSearch');

Route::get('/', 'HomeController@index');

Route::get('/random', 'CharacterController@randomCharacterRedirect');

Route::get('/browse', 'CharacterController@index');
Route::get('/all', 'CharacterController@index');

Route::get('/character/{char}', 'CharacterController@showSingle');

Route::get('/notfound', 'CharacterController@notfound');

Route::get('/debug/{char}', 'CharacterController@debug');

Route::get('/about', function() {
    return view('about');
});
