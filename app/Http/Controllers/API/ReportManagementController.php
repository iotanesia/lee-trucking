<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\CoaActivity;
use App\Models\UserDetail;
use App\Models\ExpeditionActivity;
use App\Models\ExStatusActivity;
use Auth;
use DB;
use Carbon\Carbon;
use App\Models\StkRepairHeader;
use App\Models\StkHistorySparePart;
// use App\Services\FirebaseServic\Messaging;

class ReportManagementController extends Controller
{
  //Jurnal Report
    public function getListJurnalReport(Request $request) {
        if($request->isMethod('GET')) {
          $data = $request->all();
          $whereField = 'coa_master_sheet.jurnal_category';
          $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
          $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
          $startDate = $data['start_date'].' 00:00:00';
          $endDate = $data['end_date'].' 23:59:59';
          $filterSelect = $data['filter_select'];
          $filterAktiviti = $data['filter_aktiviti'];
          $jurnalReportList = CoaActivity::leftJoin('coa_master_sheet' ,'coa_activity.coa_id','coa_master_sheet.id')
          ->leftJoin('public.users','coa_activity.created_by','public.users.id')
          ->leftJoin('coa_master_rekening','coa_activity.rek_id','coa_master_rekening.id')
          ->leftJoin('expedition_activity','coa_activity.ex_id', 'expedition_activity.id')
          ->where('coa_master_sheet.report_active','True')
          ->whereBetween('coa_activity.created_at', [$startDate, $endDate])
          ->where(function($query) use($filterSelect) {
            if($filterSelect) {
                $query->where('coa_master_sheet.jurnal_category', $filterSelect);
            }
          })
          ->where(function($query) use($filterAktiviti) {
            if($filterAktiviti) {
                $query->where('coa_master_sheet.sheet_name', $filterAktiviti);
            }
          })
          ->select('coa_activity.created_at','coa_master_sheet.sheet_name'
                  ,'coa_master_sheet.jurnal_category','public.users.name'
                  ,'coa_master_rekening.bank_name','coa_master_rekening.rek_name'
                  ,'coa_master_rekening.rek_no','coa_activity.nominal','coa_activity.table_id'
                  ,'coa_activity.table','expedition_activity.nomor_inv','expedition_activity.nomor_surat_jalan')
                  ->orderBy('coa_activity.created_at','DESC')->get();
          
          foreach($jurnalReportList as $row) {
            $row->activity_name = $row->sheet_name.' ['.$row->table_id.' ]';
            $row->nominal_debit = null;
            $row->nominal_credit = null;
            if($row->jurnal_category == 'DEBIT'){
              $row->nominal_debit = 'Rp.'. number_format($row->nominal, 0, ',', '.');
            }else if($row->jurnal_category == 'CREDIT'){
              $row->nominal_credit = 'Rp.'. number_format($row->nominal, 0, ',', '.');
            }
            $row->data_json = $row->toJson();
          }
          //  dd($jurnalReportList);
          return datatables($jurnalReportList)->toJson();
      }
      
    }
    //Jurnal Report

    //Invoice Report
    public function getListInvoiceBOReport(Request $request){
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
        $startDate = $data['start_date'].' 00:00:00';
        $endDate = $data['end_date'].' 23:59:59';
        $filterPembayaran = $data['filter'];
        $data = ExpeditionActivity::leftJoin('ex_master_ojk' ,'expedition_activity.ojk_id','ex_master_ojk.id')
        ->leftJoin('ex_wil_kabupaten','ex_master_ojk.kabupaten_id','ex_wil_kabupaten.id')
        ->leftJoin('ex_master_truck','expedition_activity.truck_id','ex_master_truck.id')
        ->where('expedition_activity.nomor_surat_jalan','iLike','BO%')
        ->where(function($query) use($filterPembayaran) {
          if($filterPembayaran) {
            if($filterPembayaran != 'Semua'){
              $query->where('expedition_activity.otv_payment_method', $filterPembayaran);
            }
          }
        })
        ->select(DB::raw('COUNT("ojk_id") AS rit'),'expedition_activity.tgl_po'
                ,'expedition_activity.nomor_inv'
                ,'ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
                ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
                ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
                ,'expedition_activity.toko','expedition_activity.harga_otv')
                ->whereBetween('expedition_activity.tgl_po', [$startDate, $endDate])
               ->groupBy('expedition_activity.tgl_po','expedition_activity.nomor_inv'
               ,'ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
                ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
                ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
                ,'expedition_activity.toko','expedition_activity.harga_otv')->get();
                foreach($data as $row) {
                    $row->harga_per_rit = 'Rp.'. number_format($row->harga_otv, 0, ',', '.');
                    $row->total = 'Rp.'. number_format(($row->rit*$row->harga_otv), 0, ',', '.');
                    
                  $row->data_json = $row->toJson();
                }
          return datatables($data)->toJson();
      }
    }

    public function getListInvoiceBAReport(Request $request){
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
        $startDate = $data['start_date'].' 00:00:00';
        $endDate = $data['end_date'].' 23:59:59';
        $filterPembayaran = $data['filter'];
        $data = ExpeditionActivity::leftJoin('ex_master_ojk' ,'expedition_activity.ojk_id','ex_master_ojk.id')
        ->leftJoin('ex_wil_kabupaten','ex_master_ojk.kabupaten_id','ex_wil_kabupaten.id')
        ->leftJoin('ex_master_truck','expedition_activity.truck_id','ex_master_truck.id')
        ->where('expedition_activity.nomor_surat_jalan','iLike','BA%')
        ->where(function($query) use($filterPembayaran) {
          if($filterPembayaran) {
            if($filterPembayaran != 'Semua'){
              $query->where('expedition_activity.otv_payment_method', $filterPembayaran);
            }
          }
        })
        ->select(DB::raw('COUNT("ojk_id") AS rit'),'expedition_activity.tgl_po'
                ,'expedition_activity.nomor_inv'
                ,'ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
                ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
                ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
                ,'expedition_activity.toko','expedition_activity.harga_otv')
                ->whereBetween('expedition_activity.tgl_po', [$startDate, $endDate])
                ->groupBy('expedition_activity.tgl_po'
                ,'expedition_activity.nomor_inv'
                ,'ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
                ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
                ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
                ,'expedition_activity.toko','expedition_activity.harga_otv')->get();
                foreach($data as $row) {
                    $row->harga_per_rit = 'Rp.'. number_format($row->harga_otv, 0, ',', '.');
                    $row->total = 'Rp.'. number_format(($row->rit*$row->harga_otv), 0, ',', '.');
                    
                  $row->data_json = $row->toJson();
                }
          return datatables($data)->toJson();
      }
    }

    public function getListInvoiceBJReport(Request $request){
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
        $startDate = $data['start_date'].' 00:00:00';
        $endDate = $data['end_date'].' 23:59:59';
        $filterPembayaran = $data['filter'];
        $data = ExpeditionActivity::leftJoin('ex_master_ojk' ,'expedition_activity.ojk_id','ex_master_ojk.id')
        ->leftJoin('ex_wil_kabupaten','ex_master_ojk.kabupaten_id','ex_wil_kabupaten.id')
        ->leftJoin('ex_master_truck','expedition_activity.truck_id','ex_master_truck.id')
        ->where('expedition_activity.nomor_surat_jalan','iLike','BJ%')
        ->where(function($query) use($filterPembayaran) {
          if($filterPembayaran) {
            if($filterPembayaran != 'Semua'){
              $query->where('expedition_activity.otv_payment_method', $filterPembayaran);
            }
          }
        })
        ->select(DB::raw('COUNT("ojk_id") AS rit'),'expedition_activity.tgl_po'
                ,'expedition_activity.nomor_inv'
                ,'ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
                ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
                ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
                ,'expedition_activity.toko','expedition_activity.harga_otv')
                ->whereBetween('expedition_activity.tgl_po', [$startDate, $endDate])
                ->groupBy('expedition_activity.tgl_po'
                ,'expedition_activity.nomor_inv'
                ,'ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
                ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
                ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
                ,'expedition_activity.toko','expedition_activity.harga_otv')->get();
                foreach($data as $row) {
                    $row->harga_per_rit = 'Rp.'. number_format($row->harga_otv, 0, ',', '.');
                    $row->total = 'Rp.'. number_format(($row->rit*$row->harga_otv), 0, ',', '.');
                    
                  $row->data_json = $row->toJson();
                }
          return datatables($data)->toJson();
      }
    }
    //Invoice Report

    //Truck Repairs Report
    public function getListTruckRepair(Request $request){
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
        $startDate = (isset($data['start_date'])) ? $data['start_date'].' 00:00:00' : '';
        $endDate = (isset($data['end_date'])) ? $data['end_date'].' 23:59:59' : '';
        $data = $data = StkRepairHeader::leftJoin('ex_master_truck' ,'stk_repair_header.truck_id','ex_master_truck.id')
        ->where(function($query) use($startDate, $endDate) {
          if($startDate && $endDate){
            $query->whereBetween('stk_repair_header.created_at', [$startDate, $endDate]);
          }
        })
        ->select('stk_repair_header.*', 'ex_master_truck.truck_name','ex_master_truck.truck_plat')
        ->orderBy('stk_repair_header.updated_at','DESC')->get();
          
        $totals = 0;
        foreach($data as $row) {
          $historyStok = StkHistorySparePart::where('header_id', $row->id)->where('transaction_type','OUT')->get();
          foreach($historyStok as $rowHistory){
              $totals = ($rowHistory->jumlah_stok * $rowHistory->amount);
          }
            $row->total = 'Rp.'. number_format($totals, 0, ',', '.');
            $row->data_json = $row->toJson();
        }
        return datatables($data)->toJson();
      }
    }

    public function getListDetailTruckRepair(Request $request){
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
        $startDate = (isset($data['start_date'])) ? $data['start_date'].' 00:00:00' : '';
        $endDate = (isset($data['end_date'])) ? $data['end_date'].' 23:59:59' : '';
        $idHeader = $data['id_header'];
        $data = $data = StkHistorySparePart::leftJoin('stk_repair_header' ,'stk_repair_header.id','stk_history_stock.header_id')
        ->where('stk_history_stock.header_id', $idHeader)
        ->where('stk_history_stock.transaction_type','OUT')
        ->where(function($query) use($startDate, $endDate) {
          if($startDate && $endDate){
            $query->whereBetween('stk_history_stock.created_at', [$startDate, $endDate]);
          }
        })
        ->select('stk_history_stock.*')
        ->orderBy('stk_history_stock.updated_at','DESC')->get();
          
        foreach($data as $row) {
          $row->total = ($row->jumlah_stok * $row->amount);
        }
        return datatables($data)->toJson();
      }
    }
    //Truck Repairs Report

    //Expedition And Rit Report  
    public function getListRitDriver(Request $request){
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
        $startDate = (isset($data['start_date'])) ? $data['start_date'].' 00:00:00' : '';
        $endDate = (isset($data['end_date'])) ? $data['end_date'].' 23:59:59' : '';
        $statusCode = isset($data['status_code']) ? $data['status_code'] : '';
        $data  = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
        ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
        ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
        ->where('expedition_activity.is_deleted','false')
        ->where(function($query) use($statusCode) {
          if($statusCode) {
              $query->where('expedition_activity.status_activity', $statusCode);
          }
        })
        ->where(function($query) use($startDate, $endDate) {
          if($startDate && $endDate){
            $query->whereBetween('expedition_activity.created_at', [$startDate, $endDate]);
          }
        })
        ->select(DB::raw('COUNT("driver_id") AS total_ekspedisi'),'expedition_activity.driver_id', 'ex_master_driver.driver_name')
        ->groupBy('expedition_activity.driver_id', 'ex_master_driver.driver_name')->get();
          // dd($data);
        return datatables($data)->toJson();
      }
    }

    public function getListRitTruck(Request $request){
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
        $startDate = (isset($data['start_date'])) ? $data['start_date'].' 00:00:00' : '';
        $endDate = (isset($data['end_date'])) ? $data['end_date'].' 23:59:59' : '';
        $statusCode = isset($data['status_code']) ? $data['status_code'] : '';
        $data  = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
        ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
        ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
        ->where('expedition_activity.is_deleted','false')
        ->where(function($query) use($statusCode) {
          if($statusCode) {
              $query->where('expedition_activity.status_activity', $statusCode);
          }
        })
        ->where(function($query) use($startDate, $endDate) {
          if($startDate && $endDate){
            $query->whereBetween('expedition_activity.created_at', [$startDate, $endDate]);
          }
        })
        ->select(DB::raw('COUNT("truck_id") AS total_ekspedisi'),'expedition_activity.truck_id', 'ex_master_truck.truck_plat','ex_master_truck.truck_name')
        ->groupBy('expedition_activity.truck_id', 'ex_master_truck.truck_plat','ex_master_truck.truck_name')->get();
          // dd($data);
        return datatables($data)->toJson();
      }
    }

    public function getListRitTujuan(Request $request){
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
        $startDate = (isset($data['start_date'])) ? $data['start_date'].' 00:00:00' : '';
        $endDate = (isset($data['end_date'])) ? $data['end_date'].' 23:59:59' : '';
        $statusCode = isset($data['status_code']) ? $data['status_code'] : '';
        $data  = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
        ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
        ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
        ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
        ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
        ->where('expedition_activity.is_deleted','false')
        ->where(function($query) use($statusCode) {
          if($statusCode) {
              $query->where('expedition_activity.status_activity', $statusCode);
          }
        })
        ->where(function($query) use($startDate, $endDate) {
          if($startDate && $endDate){
            $query->whereBetween('expedition_activity.created_at', [$startDate, $endDate]);
          }
        })
        ->select(DB::raw('COUNT("expedition_activity") AS total_ekspedisi'),'expedition_activity.ojk_id','ex_wil_kabupaten.kabupaten','ex_wil_kecamatan.kecamatan')
        ->groupBy('expedition_activity.ojk_id', 'ex_wil_kabupaten.kabupaten','ex_wil_kecamatan.kecamatan')->get();
          // dd($data);
        return datatables($data)->toJson();
      }
    }

    public function getDetailListRit(Request $request){
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
        $startDate = (isset($data['start_date'])) ? $data['start_date'].' 00:00:00' : '';
        $endDate = (isset($data['end_date'])) ? $data['end_date'].' 23:59:59' : '';
        $statusCode = isset($data['status_code']) ? $data['status_code'] : '';
        $ritBy = isset($data['rit_by']) ? $data['rit_by'] : '';
        $data  = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
        ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
        ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
        ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
        ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
        ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
        ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
        ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
        ->where('expedition_activity.is_deleted','false')
        ->where(function($query) use($ritBy, $whereValue) {
          if($ritBy && $whereValue) {
              if($ritBy == 'Tujuan'){
                $query->where('expedition_activity.ojk_id', $whereValue);
              }else if($ritBy == 'Truck'){
                $query->where('expedition_activity.truck_id', $whereValue);
              }else if($ritBy == 'Driver'){
                $query->where('expedition_activity.driver_id', $whereValue);
              }
          }
        })
        ->where(function($query) use($statusCode) {
          if($statusCode) {
              $query->where('expedition_activity.status_activity', $statusCode);
          }
        })
        ->where(function($query) use($startDate, $endDate) {
          if($startDate && $endDate){
            $query->whereBetween('expedition_activity.created_at', [$startDate, $endDate]);
          }
        })
        ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'all_global_param.param_code as status_code', 
        'ex_master_driver.driver_name', 'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name')
           ->get();
        foreach($data as $row){
          $row->tujuan = $row->kabupaten.' '.$row->kecamatan.' '.$row->cabang_name;
        }
        //  dd($data);
        return datatables($data)->toJson();
      }
    }

    public function getDetailListRitTruck(Request $request){
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
        $startDate = (isset($data['start_date'])) ? $data['start_date'].' 00:00:00' : '';
        $endDate = (isset($data['end_date'])) ? $data['end_date'].' 23:59:59' : '';
        $statusCode = isset($data['status_code']) ? $data['status_code'] : '';
        $data  = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
        ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
        ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
        ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
        ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
        ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
        ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
        ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
        ->where('expedition_activity.is_deleted','false')
        ->where('ex_master_ojk.id', $request->ojk_id)
        ->where(function($query) use($statusCode) {
          if($statusCode) {
              $query->where('expedition_activity.status_activity', $statusCode);
          }
        })
        ->where(function($query) use($startDate, $endDate) {
          if($startDate && $endDate){
            $query->whereBetween('expedition_activity.created_at', [$startDate, $endDate]);
          }
        })
        ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'all_global_param.param_code as status_code', 
        'ex_master_driver.driver_name', 'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name')
           ->get();
        foreach($data as $row){
          $row->tujuan = $row->kabupaten.' '.$row->kecamatan.' '.$row->cabang_name;
        }
        //  dd($data);
        return datatables($data)->toJson();
      }
    }
    //Expedition And Rit Report

    
}
