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
  Route::group(['as' => 'api-user', 'prefix' => 'user'], function() {
    Route::get('/', 'Services\UserController@index');
    Route::get('details', 'API\UserController@details');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\UserController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'Services\UserController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'Services\UserController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'Services\UserController@delete']);
  });

  Route::group(['as' => 'api-truck', 'prefix' => 'truck'], function() {
    Route::get('/', 'Api\TruckController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\TruckController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\TruckController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\TruckController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\TruckController@delete']);
  });

  Route::group(['as' => 'api-transaksi', 'prefix' => 'transaksi'], function() {
    Route::get('/', 'Services\TransaksiController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'Services\TransaksiController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'Services\TransaksiController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'Services\TransaksiController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'Services\TransaksiController@delete']);
  });


  Route::group(['as' => 'api-driver', 'prefix' => 'driver'], function() {
    Route::get('/', 'Services\DriverController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'Services\DriverController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'Services\DriverController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'Services\DriverController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'Services\DriverController@delete']);
  });


  Route::group(['as' => 'api-coa', 'prefix' => 'coa'], function() {
    Route::get('/', 'Services\CoaController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'Services\CoaController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'Services\CoaController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'Services\CoaController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'Services\CoaController@delete']);
  });


  Route::group(['as' => 'api-sparepart', 'prefix' => 'sparepart'], function() {
    Route::get('/', 'Services\SparePartController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'Services\SparePartController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'Services\SparePartController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'Services\SparePartController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'Services\SparePartController@delete']);
  });
  
});

Route::group(['as' => 'api-global-param', 'prefix' => 'global-param'], function() {
    Route::get('/', 'Services\GlobalParamController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\GlobalParamController@getList']);
});

