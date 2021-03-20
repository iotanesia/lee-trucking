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
Route::get('/reward', 'RewardController@index')->name('reward');
Route::get('/spareparts', 'SparePartsController@index')->name('spareparts');
Route::get('/gudang', 'GudangController@index')->name('gudang');
Route::get('/spareparts-group', 'StkGroupSparePartController@index')->name('spareparts-group');
Route::get('/ojk', 'OjkController@index')->name('ojk');
Route::get('/tenan', 'HomeController@indexTenan')->name('home');
Route::get('/transaksi', 'HomeController@indexTrx')->name('home');
Route::get('/user-detail/{id}', 'HomeController@userDetail');
Route::post('/update-user-detail', 'HomeController@updateUserDetail')->name('update-user-detail');
Route::get('/expedition', 'ExpeditionController@index')->name('expedition');
Route::get('/expedition-tracking', 'ExpeditionController@indexTracking')->name('expedition-tracking');
Route::get('/expedition-tracking/{id}', 'ExpeditionController@detailTracking')->name('expedition-detail-tracking');
Route::get('/approval-ojk-driver', 'ExpeditionController@indexApprove')->name('approval-ojk-driver');
Route::get('/approval-otv', 'ExpeditionController@indexApproveOtv')->name('approval-otv');
Route::get('/coa', 'CoaController@index')->name('coa');
Route::get('/user', 'UserController@index')->name('user');
Route::get('/role', 'GroupController@index')->name('group');
Route::get('/role/{id}', 'GroupController@detail')->name('group');
Route::post('/update-role', 'GroupController@updateRole')->name('update-role');
Route::get('/my-profile', 'HomeController@myProfile')->name('my-profile');
Route::post('/update-profile', 'HomeController@updateProfile')->name('update-profile');
Route::get('/bonus-driver-rit', 'BonusDriverRitController@indexRit')->name('bonus-driver-rit');
Route::get('/bonus-driver-reward', 'BonusDriverRitController@indexReward')->name('bonus-driver-reward');
Route::get('/purchased-sparepart', 'SparePartsController@indexpurchase')->name('spareparts');
Route::get('/hutang-stok', 'SparePartsController@indexUnpaid')->name('hutang-stok');
Route::get('/repair-truck', 'StkRepairHeaderController@index')->name('repair-truck');
Route::get('/repair-ban-truck', 'StkRepairBanHeaderController@index')->name('repair-ban-truck');
Route::get('/pinjaman-karyawan', 'MoneyTransactionHeaderController@index')->name('pinjaman-karyawan');
Route::get('/penanaman-modal', 'MoneyTransactionHeaderController@indexModal')->name('penanaman-modal');
Route::get('/jurnal-report', 'JurnalController@index')->name('jurnal-report');
Route::get('/invoice-report', 'InvoiceController@index')->name('invoice-report');
Route::get('/dashboard/{schema}', 'DashboardController@index');
//export excel invoice
Route::get('/export-bo', 'InvoiceController@exportExcelBO')->name('export-bo');
Route::get('/export-ba', 'InvoiceController@exportExcelBA')->name('export-ba');
Route::get('/export-bj', 'InvoiceController@exportExcelBJ')->name('export-bj');

Route::get('/getbarcode/{code}', 'SparePartsController@getBarcode');

//repair truck Report
Route::get('/repair-truck-report', 'RepairTruckReportController@index')->name('repair-truck-report');

//Expedisi dan rit Report
Route::get('/ekspedisi-rit-report', 'ExpeditionAndRitReportController@index')->name('ekspedisi-rit-report');

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
