<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->namespace('Api\V1')->name('api.v1.')->group(function() {
    Route::post('/token', 'UserController@updatedApi');
    Route::apiResource('task', 'TaskController');
    Route::prefix('task')->name('task.')->group(function() {
        Route::post('/approve', 'TaskController@approveSuggestionTask')->name('approve');
        Route::post('/report', 'TaskController@report')->name('report');
        Route::post('/confirmed', 'TaskController@getConfirmedTasks')->name('confirmed');
    });
    Route::apiResource('group', 'GroupController');
    Route::apiResource('category', 'CategoryController')->only(['index', 'store']);
    Route::prefix('group')->name('group.')->group(function() {
        Route::post('/enter', 'GroupController@enter')->name('enter');
    });
    Route::post('flashMessage', 'FlashMessageController@store')->name('flashMessage.store');
});
