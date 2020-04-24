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

Route::get('setting/{id}/editFlashMessage', 'SettingController@editFlashMessage');
Route::put('setting/{id}/updateFlashMessage', 'SettingController@updateFlashMessage');
Route::delete('setting/{id}/destroyFlashMessage', 'SettingController@destroyFlashMessage');

Route::resource('task', 'TaskController');
