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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'VoteController@index')->name('home');
Route::get('/index', 'VoteController@index');

Route::post('/votes/select', 'VoteController@select');
Route::post('/votes/store', 'VoteController@store');
Route::get('/votes/create', 'VoteController@create')->name('vote_create');
Route::get('/votes/{entryCode}', 'VoteController@show');

Route::post('/votes/{entryCode}/comment', 'CommentController@store');

Route::get('/users/{user}', 'HomeController@user');
Route::post('/users/change', 'HomeController@change');

Route::get('/wasteland', 'HomeController@wasteland')->name('wasterland');

Auth::routes();
//Route::get('/home', 'HomeController@index');

