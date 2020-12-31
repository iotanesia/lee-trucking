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
    Route::get('details', 'API\UserController@detailProfile');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\UserController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'Services\UserController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'Services\UserController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'Services\UserController@delete']);
    Route::post('updatePhotoProfile', ['as' => '-updateProfile', 'uses' => 'API\UserController@updatePhotoProfile']);
    Route::post('upatePassword', ['as' => '-upatePassword', 'uses' => 'API\UserController@upatePassword']);
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
    Route::get('/', 'API\DriverController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\DriverController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\DriverController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\DriverController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\DriverController@delete']);
  });

  Route::group(['as' => 'api-truck', 'prefix' => 'truck'], function() {
    Route::get('/', 'API\TruckController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\TruckController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\TruckController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\TruckController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\TruckController@delete']);
  });

  Route::group(['as' => 'api-cabang', 'prefix' => 'cabang'], function() {
    Route::get('/', 'API\CabangController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\CabangController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\CabangController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\CabangController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\CabangController@delete']);
  });

  Route::group(['as' => 'api-cabangs', 'prefix' => 'cabangs'], function() {
    Route::get('/', 'API\CabangsController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\CabangsController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\CabangsController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\CabangsController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\CabangsController@delete']);
  });

  Route::group(['as' => 'api-kenek', 'prefix' => 'kenek'], function() {
    Route::get('/', 'API\KenekController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\KenekController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\KenekController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\KenekController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\KenekController@delete']);
  });
  
  Route::group(['as' => 'api-spareparts', 'prefix' => 'spareparts'], function() {
      Route::get('/', 'API\SparePartController@index');
      Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\SparePartController@getList']);
      Route::get('get-sparepart-detail', ['as' => '-get-sparepart-detail', 'uses' => 'API\SparePartController@getListDetail']);
      Route::post('add', ['as' => '-add', 'uses' => 'API\SparePartController@add']);
      Route::post('edit', ['as' => '-edit', 'uses' => 'API\SparePartController@edit']);
      Route::post('delete', ['as' => '-delete', 'uses' => 'API\SparePartController@delete']);
      Route::post('update-stok', ['as' => '-update-stok', 'uses' => 'API\SparePartController@updateStok']);
  });
  
  Route::group(['as' => 'api-restok-sparepart', 'prefix' => 'restok-sparepart'], function() {
    Route::get('/', 'API\StkRestokSparePartController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\StkRestokSparePartController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\StkRestokSparePartController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\StkRestokSparePartController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\StkRestokSparePartController@delete']);
  });

  Route::group(['as' => 'api-group-sparepart', 'prefix' => 'group-sparepart'], function() {
    Route::get('/', 'API\StkGroupSparePartController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\StkGroupSparePartController@getList']);
    Route::get('get-list-pagination', ['as' => '-get-list-pagination', 'uses' => 'API\StkGroupSparePartController@getListPagination']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\StkGroupSparePartController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\StkGroupSparePartController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\StkGroupSparePartController@delete']);
  });

  Route::group(['as' => 'api-ojk', 'prefix' => 'ojk'], function() {
    Route::get('/', 'API\OjkController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\OjkController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\OjkController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\OjkController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\OjkController@delete']);
  });

  Route::group(['as' => 'api-expedition', 'prefix' => 'expedition'], function() {
    Route::get('/', 'API\ExpeditionController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\ExpeditionController@getList']);
    Route::get('get-ojk', ['as' => '-get-ojk', 'uses' => 'API\ExpeditionController@getOjk']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\ExpeditionController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\ExpeditionController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\ExpeditionController@delete']);
  });
});

Route::group(['as' => 'api-global-param', 'prefix' => 'global-param'], function() {
    Route::get('/', 'Services\GlobalParamController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\GlobalParamController@getList']);
});

Route::group(['as' => 'api-daerah', 'prefix' => 'daerah'], function() {
  Route::get('get-provinsi', ['as' => '-get-provinsi', 'uses' => 'API\DaerahController@getProvinsi']);
  Route::get('get-kabupaten-by-idProv', ['as' => '-get-kabupaten-by-idProv', 'uses' => 'API\DaerahController@getKabupatenByIdProvinsi']);
  Route::get('get-kecamatan-by-idKab', ['as' => '-get-kabupaten-by-idKab', 'uses' => 'API\DaerahController@getKecamatanByIdKabupaten']);
});


