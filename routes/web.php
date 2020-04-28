<?php

use Illuminate\Support\Facades\Route;

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
Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::resource('/users', 'UserController');

Route::get('/setting', 'SettingController@index');

Route::get('/setting/editCycle', 'SettingController@editCycle');
Route::put('/setting/updateCycle', 'SettingController@updateCycle');

Route::get('/setting/createFlashMessage', 'SettingController@createFlashMessage');
Route::get('setting/{id}/editFlashMessage', 'SettingController@editFlashMessage');
Route::put('setting/{id}/updateFlashMessage', 'SettingController@updateFlashMessage');
Route::delete('setting/{id}/destroyFlashMessage', 'SettingController@destroyFlashMessage');

Route::get('/bulletin/create', 'BulletinController@create');
Route::get('/bulletin/{id}/edit', 'BulletinController@edit');

Route::get('/bulletin', 'BulletinController@index');
Route::post('/bulletin', 'BulletinController@store');
Route::put('/bulletin/{id}', 'BulletinController@update');
Route::delete('/bulletin/{id}', 'BulletinController@destroy');

// Test api
Route::get('/testFlashMessage', function()
{
    return view('testApi.testFlashMessageApi');
});


Route::prefix('task')->name('task.')->group(function() {
    Route::get('/', 'TaskController@index')->name('index');
    Route::get('/edit', 'TaskController@create')->name('edit');
    Route::get('/history', 'TaskController@history')->name('history');
});


Route::get('/profile/{id}', 'ProfileController@show');
Route::get('/profile/{id}/edit', 'ProfileController@edit');
Route::put('/profile/{id}', 'ProfileController@update');

// Route::get('/leaderboard', 'LeaderboardController@index');
Route::get('/leaderboard', 'ProfileController@index');
