<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'ImageController@index')->name('images.index');
Route::resource('images', 'ImageController');

Route::post('/generate', 'ImageController@generateLink')->name('images.generate.link');
Route::post('/regenerate', 'ImageController@reGenerateLink')->name('images.regenerate.link');

Route::post('/share', 'ImageController@shareLink')->name('images.share.link');
