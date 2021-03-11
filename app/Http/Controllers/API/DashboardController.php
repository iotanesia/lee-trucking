<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use DB;
use Validator;

class DashboardController extends Controller
{

    public function getList(Request $request) {
        if($request->isMethod('GET')) {
            $schema = Auth::user()->schema;
            $bln = date('m');
            $thn = date('Y');
            $totalEx = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".expedition_activity WHERE EXTRACT(MONTH FROM updated_at) = ".$bln."  AND EXTRACT(YEAR FROM updated_at) = ".$thn."");
            $totalClose = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".expedition_activity WHERE status_activity = 'CLOSED_EXPEDITION'");
            $totalOnProggres = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".expedition_activity WHERE status_activity <> 'CLOSED_EXPEDITION'");
            $totalrepair = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".stk_repair_header WHERE EXTRACT(MONTH FROM updated_at) = ".$bln."  AND EXTRACT(YEAR FROM updated_at) = ".$thn."");
            $totalrepairBan = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".stk_repair_header WHERE kode_repair LIKE '%RPBAN-%'");
            $totalrepairNonBan = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".stk_repair_header WHERE kode_repair LIKE '%RP-%'");
            $totaltruck = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".ex_master_truck");
            $truck = DB::select("SELECT b.cabang_name, COUNT(a.id) FROM ".$schema.".ex_master_truck AS a JOIN ".$schema.".ex_master_cabang AS b ON a.cabang_id = b.id GROUP BY cabang_id, b.cabang_name");
            $debit = DB::select("SELECT SUM(a.nominal) AS total_income FROM ".$schema.".coa_activity AS a JOIN ".$schema.".coa_master_sheet AS b ON a.coa_id = b.id WHERE report_active = 'True' AND b.jurnal_category = 'DEBIT' AND EXTRACT(MONTH FROM a.created_at) = ".$bln."  AND EXTRACT(YEAR FROM a.created_at) = ".$thn." ");
            $credit = DB::select("SELECT SUM(a.nominal) AS total_income FROM ".$schema.".coa_activity AS a JOIN ".$schema.".coa_master_sheet AS b ON a.coa_id = b.id WHERE report_active = 'True' AND b.jurnal_category = 'CREDIT' AND EXTRACT(MONTH FROM a.created_at) = ".$bln."  AND EXTRACT(YEAR FROM a.created_at) = ".$thn." ");
            $totalIncome = $debit[0]->total_income - $credit[0]->total_income;

            $data['total_expedisi'] = $totalEx[0]->total;
            $data['total_on_progress'] = $totalOnProggres[0]->total;
            $data['total_close'] = $totalClose[0]->total;
            $data['total_repair'] = $totalrepair[0]->total;
            $data['total_repairBan'] = $totalrepairBan[0]->total;
            $data['total_repairNonBan'] = $totalrepairNonBan[0]->total;
            $data['total_truck'] = $totaltruck[0]->total;
            $data['total_income'] = number_format($totalIncome,0,',','.');

            foreach($truck as $key => $val) {
                $cabangName = strtolower(str_replace(" ", "_", $val->cabang_name));
                $data[$cabangName] = $val->count;
            }
            
            return response()->json([
                'code' => 200,
                'code_message' => 'Success',
                'code_type' => 'Success',
                'data'=> $data
            ], 200);
        
        } else {
            return response()->json([
                'code' => 405,
                'code_message' => 'Method salah',
                'code_type' => 'BadRequest',
                'data'=> null
            ], 405);
        }
    }
}
