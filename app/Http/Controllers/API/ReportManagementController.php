<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\CoaActivity;
use App\Models\UserDetail;
use App\Models\ExpeditionActivity;
use Auth;
use DB;
use Carbon\Carbon;
use App\Models\StkRepairHeader;
use App\Models\StkHistorySparePart;
// use App\Services\FirebaseServic\Messaging;

class ReportManagementController extends Controller
{
    public function getListJurnalReport(Request $request) {
        if($request->isMethod('GET')) {
          $data = $request->all();
          $whereField = 'coa_master_sheet.jurnal_category';
          $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
          $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
          $jurnalReportList = CoaActivity::leftJoin('coa_master_sheet' ,'coa_activity.coa_id','coa_master_sheet.id')
          ->leftJoin('public.users','coa_activity.created_by','public.users.id')
          ->leftJoin('coa_master_rekening','coa_activity.rek_id','coa_master_rekening.id')
          ->where('coa_master_sheet.report_active','True')
          ->where(function($query) use($whereFilter) {
            if($whereFilter) {
                $query->where('coa_master_sheet.jurnal_category', $whereFilter);
            }
          })
          ->select('coa_activity.created_at','coa_master_sheet.sheet_name'
                  ,'coa_master_sheet.jurnal_category','public.users.name'
                  ,'coa_master_rekening.bank_name','coa_master_rekening.rek_name'
                  ,'coa_master_rekening.rek_no','coa_activity.nominal','coa_activity.table_id','coa_activity.table')->orderBy('coa_activity.created_at','DESC')->get();
          
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
          // dd($jurnalReportList);
          return datatables($jurnalReportList)->toJson();
      }
      
    }

    public function getListInvoiceBOReport(Request $request){
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
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
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
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
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
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

    //Truck Repairs
    public function getListTruckRepair(Request $request){
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
        $startDate = (isset($data['start_date'])) ? $data['start_date'] : '';
        $endDate = (isset($data['end_date'])) ? $data['end_date'] : '';
        $data = $data = StkRepairHeader::leftJoin('ex_master_truck' ,'stk_repair_header.truck_id','ex_master_truck.id')
        ->where(function($query) use($startDate, $endDate) {
          if($startDate && $endDate){
            $query->whereBetween('stk_repair_header.created_at', [$startDate, $endDate]);
          }
        })
        ->select('stk_repair_header.*', 'ex_master_truck.truck_name')
        ->orderBy('stk_repair_header.updated_at','DESC')->get();
          
        $totals = 0;
        foreach($data as $row) {
          $historyStok = StkHistorySparePart::where('header_id', $row->id)->where('transaction_type','OUT')->get();
          foreach($historyStok as $rowHistory){
              $totals += ($rowHistory->jumlah_stok * $rowHistory->amount);
          }
            $row->total = 'Rp.'. number_format($totals, 0, ',', '.');
            $row->data_json = $row->toJson();
        }
        return datatables($data)->toJson();
      }
    }
    
}
