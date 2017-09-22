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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('threads/create', 'ThreadsController@create');
Route::get('threads/{channel?}', 'ThreadsController@index');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show');
Route::get('profiles/{user}', 'ProfilesController@show')->name('profiles');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');

Route::post('threads/{thread}/replies', 'RepliesController@store');
Route::post('threads', 'ThreadsController@store');
Route::post('/replies/{reply}/favourites', 'FavouritesController@store');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');

Route::patch('replies/{reply}', 'RepliesController@update');

Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::delete('replies/{reply}', 'RepliesController@destroy');
Route::delete('/replies/{reply}/favourites', 'FavouritesController@destroy');
