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

Route::get('/', 'ThreadController@index')->name('home');
Route::get('/index', 'ThreadController@index');

Route::get('/threads/{entryCode}', 'ThreadController@show');
Route::get('/threads/create', 'ThreadController@create');
Route::post('/threads', 'ThreadController@store');

Route::post('/threads/{thread}/comment', 'CommentsController@store');



Auth::routes();
Route::get('/home', 'HomeController@index');

