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

// Search
Route::post('/search', 'SearchController@fetch');
Route::get('/search/{search}', 'SearchController@show')->name('search');

Route::get('/', 'HomeController@index');

Route::get('/all', 'CharacterController@index');
Route::get('/character/{char}', 'CharacterController@show');

Route::get('/notfound', 'CharacterController@notfound');

Route::get('/debug/{char}', 'CharacterController@debug');
