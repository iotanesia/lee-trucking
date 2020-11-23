<?php

use Illuminate\Http\Request;

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


Route::group(['middleware' => 'auth:api'], function() {
});



Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
 
Route::group(['middleware' => 'auth:api'], function(){
  Route::post('details', 'API\UserController@details');
  Route::group(['as' => 'api-user', 'prefix' => 'user'], function() {
    Route::get('/', 'Services\UserController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'Services\UserController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'Services\UserController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'Services\UserController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'Services\UserController@delete']);
  });

  Route::group(['as' => 'api-tenan', 'prefix' => 'tenan'], function() {
    Route::get('/', 'Services\TenanController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'Services\TenanController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'Services\TenanController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'Services\TenanController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'Services\TenanController@delete']);
  });

  Route::group(['as' => 'api-transaksi', 'prefix' => 'transaksi'], function() {
    Route::get('/', 'Services\TransaksiController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'Services\TransaksiController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'Services\TransaksiController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'Services\TransaksiController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'Services\TransaksiController@delete']);
  });


});

