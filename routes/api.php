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

Route::apiResource('/images', 'APIController\ImageController');
Route::get('/generate/{imageId}', 'APIController\ImageController@generateLink');
Route::get('/regenerate/{imageId}', 'APIController\ImageController@reGenerateLink');
Route::get('/share/{linkId}', 'APIController\ImageController@shareLink');
Route::get('/statistics', 'APIController\ImageController@statistics');
Route::post('/search', 'APIController\ImageController@searchImageByName');
