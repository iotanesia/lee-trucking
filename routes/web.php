<?php

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
    return redirect('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/truck', 'TruckController@index')->name('truck');
Route::get('/cabang', 'CabangController@index')->name('cabang');
Route::get('/driver', 'DriverController@index')->name('driver');
Route::get('/kenek', 'KenekController@index')->name('kenek');
Route::get('/spareparts', 'SparepartsController@index')->name('spareparts');
Route::get('/spareparts-group', 'StkGroupSparePartController@index')->name('spareparts-group');
Route::get('/ojk', 'OjkController@index')->name('ojk');
Route::get('/tenan', 'HomeController@indexTenan')->name('home');
Route::get('/transaksi', 'HomeController@indexTrx')->name('home');
Route::get('/user-detail/{id}', 'HomeController@userDetail');
Route::post('/update-user-detail', 'HomeController@updateUserDetail')->name('update-user-detail');
