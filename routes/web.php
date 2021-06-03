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

Route::get('/', function () {
    return view('welcome');
});

Route::get('picture', 'App\Http\Controllers\PictureController@create')->name('picture.create');
Route::post('picture', 'App\Http\Controllers\PictureController@store')->name('picture.download');
Route::get('{short_url}', 'App\Http\Controllers\PictureController@getPicture');
