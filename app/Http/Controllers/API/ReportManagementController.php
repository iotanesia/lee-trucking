<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\CoaActivity;
use App\Models\UserDetail;
use Auth;
use DB;
use Carbon\Carbon;
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

}
