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

Route::get('chatSession', function () {
    return session('chat');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('tasks', 'TaskListController@index');
Route::get('taskdata', 'TaskListController@data');
Route::patch('task/{id}', 'TaskListController@update');
Route::delete('task/{id}', 'TaskListController@destroy');
Route::post('task', 'TaskListController@store');

Route::group(['middleware' => ['auth']], function () {
    Route::get('chat', 'ChatController@chat');
    Route::post('chat', 'ChatController@send');
    Route::post('saveToSession', 'ChatController@saveToSession');
    Route::post('getOldMessages', 'ChatController@getOldMessages');
    Route::post('deleteSession', 'ChatController@deleteSession');
});
