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
Route::get('threads/{channel?}', 'ThreadsController@index')->name('threads.index');
Route::get('threads/{channel}/{thread}', 'ThreadsController@show');
Route::get('profiles/{user}', 'ProfilesController@show')->name('profiles');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');
Route::get('/api/users', 'Api\UsersController@index');
Route::get('/register/confirm', 'Api\RegisterConfirmationController@index')->name('register.confirm');


Route::post('threads/{thread}/replies', 'RepliesController@store');
Route::post('threads', 'ThreadsController@store')->middleware('must-be-confirmed')->name('threads.store');
Route::post('/replies/{reply}/favourites', 'FavouritesController@store');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptions@store');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarsController@store')->middleware('auth')->name('avatar');
Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');

Route::patch('replies/{reply}', 'RepliesController@update');

Route::delete('threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::delete('replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');
Route::delete('/replies/{reply}/favourites', 'FavouritesController@destroy');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptions@destroy');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');
