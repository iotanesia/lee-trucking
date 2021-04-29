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
 
Route::group(['middleware' => 'auth:api'], function(){
  Route::post('register', 'API\UserController@register');
  Route::group(['as' => 'api-user', 'prefix' => 'user'], function() {
    Route::get('/', 'Services\UserController@index');
    Route::get('details', ['as' => '-details', 'uses' => 'API\UserController@detailProfile']);
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\UserController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\UserController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\UserController@edit']);
    Route::post('edit-profile', ['as' => '-edit', 'uses' => 'API\UserController@editProfile']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\UserController@delete']);
    Route::post('updatePhotoProfile', ['as' => '-updateProfile', 'uses' => 'API\UserController@updatePhotoProfile']);
    Route::post('updatePassword', ['as' => '-updatePassword', 'uses' => 'API\UserController@updatePassword']);
    Route::post('updateFcm', ['as' => '-updateFcm', 'uses' => 'API\UserController@updateFcm']);
  });

  Route::group(['as' => 'api-group', 'prefix' => 'group'], function() {
    Route::get('/', 'Api\GroupController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\GroupController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\GroupController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\GroupController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\GroupController@delete']);
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
    Route::get('get-user-driver-list', ['as' => '-get-user-driver-list', 'uses' => 'API\DriverController@getUserDriverList']);
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

  Route::group(['as' => 'api-reward', 'prefix' => 'reward'], function() {
    Route::get('/', 'API\RewardController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\RewardController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\RewardController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\RewardController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\RewardController@delete']);
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
      Route::get('get-list-all', ['as' => '-get-list-all', 'uses' => 'API\SparePartController@getListAll']);
      Route::get('get-list-all-ban', ['as' => '-get-list-all-ban', 'uses' => 'API\SparePartController@getListAllBan']);
      Route::get('get-list-unpaid', ['as' => '-get-list-unpaid', 'uses' => 'API\SparePartController@getListUnpaid']);
      Route::get('get-sparepart-detail', ['as' => '-get-sparepart-detail', 'uses' => 'API\SparePartController@getListDetail']);
      Route::get('get-sparepart-detail-history', ['as' => '-get-sparepart-detail-history', 'uses' => 'API\SparePartController@getListDetailHistory']);
      Route::post('add', ['as' => '-add', 'uses' => 'API\SparePartController@add']);
      Route::post('edit', ['as' => '-edit', 'uses' => 'API\SparePartController@edit']);
      Route::post('delete', ['as' => '-delete', 'uses' => 'API\SparePartController@delete']);
      Route::post('update-stok', ['as' => '-update-stok', 'uses' => 'API\SparePartController@updateStok']);
      Route::post('paid', ['as' => '-paid', 'uses' => 'API\SparePartController@paid']);
      Route::post('barcode', ['as' => '-barcode', 'uses' => 'API\SparePartController@barcode']);
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

  Route::group(['as' => 'api-warehouse', 'prefix' => 'warehouse'], function() {
    Route::get('/', 'API\WareHouseController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\WareHouseController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\WareHouseController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\WareHouseController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\WareHouseController@delete']);
    Route::post('update-stok', ['as' => '-update-stok', 'uses' => 'API\WareHouseController@updateStok']);
    Route::get('get-sparepart-detail', ['as' => '-get-sparepart-detail', 'uses' => 'API\WareHouseController@getListDetail']);
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
    Route::get('get-detail-by-param', ['as' => '-get-detail-by-param', 'uses' => 'API\ExpeditionController@getDetailExpeditionByInvoiceAndNoSuratJalan']);
    Route::get('get-list-approval-ojk', ['as' => '-get-list-approval-ojk', 'uses' => 'API\ExpeditionController@getListApprovalOjk']);
    Route::get('get-list-approval-otv', ['as' => '-get-list-approval-otv', 'uses' => 'API\ExpeditionController@getListApprovalOtv']);
    Route::get('get-list-history-by-param', ['as' => '-get-list-history-by-param', 'uses' => 'API\ExpeditionController@getExpeditionHistoryByNoInvOrNoSuratJalan']);
    Route::get('get-list-expedition-driver', ['as' => '-get-list-expedition-driver', 'uses' => 'API\ExpeditionController@getExpeditionHistoryByDriver']);
    Route::get('get-list-expedition-driver-selesai', ['as' => '-get-list-expedition-driver-selesai', 'uses' => 'API\ExpeditionController@getExpeditionHistoryByDriverSelesai']);
    Route::get('get-list-expedition-by-driver-periode', ['as' => '-get-list-expedition-by-driver-periode', 'uses' => 'API\ExpeditionController@getExpeditionHistoryByIdDriverAndPeriode']);
    Route::get('get-ojk', ['as' => '-get-ojk', 'uses' => 'API\ExpeditionController@getOjk']);
    Route::get('get-kenek', ['as' => '-get-kenek', 'uses' => 'API\ExpeditionController@getKenek']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\ExpeditionController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\ExpeditionController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\ExpeditionController@delete']);
  });

  Route::group(['as' => 'api-coa', 'prefix' => 'coa'], function() {
    Route::get('/', 'API\CoaController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\CoaController@getList']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\CoaController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\CoaController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\CoaController@delete']);
  });

  Route::group(['as' => 'api-bonusDriverRit', 'prefix' => 'bonusDriverRit'], function() {
    Route::get('/', 'API\BonusDriverRitController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\BonusDriverRitController@getList']);
    Route::get('get-list-kenek', ['as' => '-get-list-kenek', 'uses' => 'API\BonusDriverRitController@getListKenek']);
    Route::get('get-list-by-periode', ['as' => '-get-list-by-periode', 'uses' => 'API\BonusDriverRitController@getListByPeriode']);
    Route::get('get-list-by-tujuan', ['as' => '-get-list-by-tujuan', 'uses' => 'API\BonusDriverRitController@getListByTujuan']);
    Route::get('get-list-by-tujuan-detail', ['as' => '-get-list-by-tujuan-detail', 'uses' => 'API\BonusDriverRitController@getListByTujuanDetail']);
    Route::get('get-kenek-bonus-list-by-periode', ['as' => '-get-kenek-bonus-list-by-periode', 'uses' => 'API\BonusDriverRitController@getKenekBonusListByPeriode']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\BonusDriverRitController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\BonusDriverRitController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\BonusDriverRitController@delete']);
  });

  Route::group(['as' => 'api-stkRepairHeader', 'prefix' => 'stkRepairHeader'], function() {
    Route::get('/', 'API\StkRepairHeaderController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\StkRepairHeaderController@getList']);
    Route::get('get-list-by-driver', ['as' => '-get-list-by-driver', 'uses' => 'API\StkRepairHeaderController@getListByDriver']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\StkRepairHeaderController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\StkRepairHeaderController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\StkRepairHeaderController@delete']);
    Route::post('add-mobile', ['as' => '-add-mobile', 'uses' => 'API\StkRepairHeaderController@addMobile']);
    Route::post('edit-mobile', ['as' => '-edit-mobile', 'uses' => 'API\StkRepairHeaderController@editMobile']);
  });

  Route::group(['as' => 'api-stkRepairBanHeader', 'prefix' => 'stkRepairBanHeader'], function() {
    Route::get('/', 'API\StkRepairBanHeaderController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\StkRepairBanHeaderController@getList']);
    Route::get('get-list-by-driver', ['as' => '-get-list-by-driver', 'uses' => 'API\StkRepairBanHeaderController@getListByDriver']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\StkRepairBanHeaderController@add']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\StkRepairBanHeaderController@edit']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\StkRepairBanHeaderController@delete']);
  });

  Route::group(['as' => 'api-notification', 'prefix' => 'notification'], function() {
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\NotificationController@getList']);
    Route::get('get-count', ['as' => '-get-count', 'uses' => 'API\NotificationController@getCount']);
    Route::post('is-read', ['as' => '-is-read', 'uses' => 'API\NotificationController@isRead']);
    Route::post('read-all', ['as' => '-read-all', 'uses' => 'API\NotificationController@readAll']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\NotificationController@delete']);
    Route::post('tes', ['as' => '-tes', 'uses' => 'API\ExpeditionController@testNotif']);
  });

  Route::group(['as' => 'api-moneyTransactionHeader', 'prefix' => 'moneyTransactionHeader'], function() {
    Route::get('/', 'API\MoneyTransactionHeaderController@index');
    Route::get('get-list', ['as' => '-get-list', 'uses' => 'API\MoneyTransactionHeaderController@getList']);
    Route::get('get-list-by-driver', ['as' => '-get-list-by-driver', 'uses' => 'API\MoneyTransactionHeaderController@getListByDriver']);
    Route::get('get-list-modal-usaha', ['as' => '-get-list-modal-usaha', 'uses' => 'API\MoneyTransactionHeaderController@getListModalUsaha']);
    Route::get('get-list-detail', ['as' => '-get-list-detail', 'uses' => 'API\MoneyTransactionHeaderController@getListDetail']);
    Route::get('get-list-termin', ['as' => '-get-list-termin', 'uses' => 'API\MoneyTransactionHeaderController@getListDetailPembayaran']);
    Route::post('add', ['as' => '-add', 'uses' => 'API\MoneyTransactionHeaderController@add']);
    Route::post('add-modal-usaha', ['as' => '-add-modal-usaha', 'uses' => 'API\MoneyTransactionHeaderController@addModalUsaha']);
    Route::post('paid', ['as' => '-paid', 'uses' => 'API\MoneyTransactionHeaderController@paid']);
    Route::post('paid-modal-usaha', ['as' => '-paid-modal-usaha', 'uses' => 'API\MoneyTransactionHeaderController@paidModalUsaha']);
    Route::post('edit', ['as' => '-edit', 'uses' => 'API\MoneyTransactionHeaderController@edit']);
    Route::post('edit-modal-usaha', ['as' => '-edit-modal-usaha', 'uses' => 'API\MoneyTransactionHeaderController@editModalUsaha']);
    Route::post('delete', ['as' => '-delete', 'uses' => 'API\MoneyTransactionHeaderController@delete']);
    Route::post('delete-modal-usaha', ['as' => '-delete-modal-usaha', 'uses' => 'API\MoneyTransactionHeaderController@deleteModalUsaha']);
  });

  Route::group(['as' => 'api-report', 'prefix' => 'report'], function() {
    Route::get('/', 'API\ReportManagementController@index');
    Route::get('get-jurnal-list', ['as' => '-get-jurnal-list', 'uses' => 'API\ReportManagementController@getListJurnalReport']);
    Route::get('get-invoice-bo-list', ['as' => '-get-invoice-bo-list', 'uses' => 'API\ReportManagementController@getListInvoiceBOReport']);
    Route::get('get-invoice-ba-list', ['as' => '-get-invoice-ba-list', 'uses' => 'API\ReportManagementController@getListInvoiceBAReport']);
    Route::get('get-invoice-bj-list', ['as' => '-get-invoice-bj-list', 'uses' => 'API\ReportManagementController@getListInvoiceBJReport']);
    Route::get('get-invoice-bf-list', ['as' => '-get-invoice-bf-list', 'uses' => 'API\ReportManagementController@getListInvoiceBFReport']);
    Route::post('post-change-status-periksa', ['as' => '-post-change-status-periksa', 'uses' => 'API\ReportManagementController@postChangeStatusPemeriksaan']);
    Route::get('get-repair-truck-list', ['as' => '-get-repair-truck-list', 'uses' => 'API\ReportManagementController@getListTruckRepair']);
    Route::get('get-detail-repair-truck-list', ['as' => '-get-detail-repair-truck-list', 'uses' => 'API\ReportManagementController@getListDetailTruckRepair']);
    Route::get('get-ekspedisi-rit-tujuan-list', ['as' => '-get-ekspedisi-rit-tujuan-list', 'uses' => 'API\ReportManagementController@getListRitTujuan']);
    Route::get('get-ekspedisi-rit-truck-list', ['as' => '-get-ekspedisi-rit-truck-list', 'uses' => 'API\ReportManagementController@getListRitTruck']);
    Route::get('get-ekspedisi-rit-driver-list', ['as' => '-get-ekspedisi-rit-driver-list', 'uses' => 'API\ReportManagementController@getListRitDriver']);
    Route::get('get-detail-rit-list', ['as' => '-get-detail-rit-list', 'uses' => 'API\ReportManagementController@getDetailListRit']);
  });
  
  Route::group(['as' => 'api-dashboard', 'prefix' => 'dashboard'], function() {
    Route::get('/', 'API\DashboardController@index');
    Route::get('get-list', ['as' => '-get-jurnal-list', 'uses' => 'API\DashboardController@getList']);
  });

  Route::group(['as' => 'api-drop-down', 'prefix' => 'drop-down'], function() {
    Route::get('/', 'Services\DropDown@index');
    Route::get('get-list-truck', ['as' => '-get-list-truck', 'uses' => 'API\DropDownController@getListTruck']);
    Route::get('get-list-driver', ['as' => '-get-list-driver', 'uses' => 'API\DropDownController@getListDriver']);
    Route::get('get-list-kenek', ['as' => '-get-list-kenek', 'uses' => 'API\DropDownController@getListKenek']);
    Route::get('get-list-all-user', ['as' => '-get-list-all-user', 'uses' => 'API\DropDownController@getListallUser']);
    Route::get('get-list-sparepart', ['as' => '-get-list-sparepart', 'uses' => 'API\DropDownController@getListSparepart']);
  });
  
});


Route::group(['as' => 'api-drop-down', 'prefix' => 'drop-down'], function() {
    Route::get('/', 'Services\DropDown@index');
    Route::get('get-list-rekening', ['as' => '-get-list-rekening', 'uses' => 'API\DropDownController@getListRekening']);
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


