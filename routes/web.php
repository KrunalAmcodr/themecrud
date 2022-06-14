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

Auth::routes();
Route::prefix('admin')->middleware('auth')->namespace('Dashboard')->group(function () {
    Route::get('/', 'HomeController@index')->name('admin');
    Route::resource('post', 'PostController');
    Route::resource('task', 'TaskController');
    Route::get('/item', 'ItemController@index')->name('item');
    Route::post('/item/create', 'ItemController@createorupdate')->name('item.create');
    Route::post('/item/update', 'ItemController@createorupdate')->name('item.update');
    Route::delete('/item/delete', 'ItemController@destroy')->name('item.delete');
});

