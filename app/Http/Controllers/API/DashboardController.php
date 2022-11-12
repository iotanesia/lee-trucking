<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\DB;
use Validator;

class DashboardController extends Controller
{

    public function getList(Request $request) {
        if($request->isMethod('GET')) {
            $cekRole = $this->checkRoles($request);
            $queryRole = "";

            if($cekRole) {
                $ids = json_decode($cekRole, true);
                $idRole = implode(', ', $ids);
                $queryRole = 'AND b.cabang_id IN ('.$idRole.')';
            }

            $schema = $request->current_user->schema;
            $bln = date('m');
            $thn = date('Y');
            $data['cabang_tsj'] = 0;
            $data['cabang_dawuan'] = 0;
            $totalEx = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".expedition_activity WHERE EXTRACT(MONTH FROM tgl_po) = ".$bln."  AND EXTRACT(YEAR FROM tgl_po) = ".$thn." AND is_deleted = 'f' ");
            $totalEx = DB::select("SELECT COUNT(a.id) AS total FROM ".$schema.".expedition_activity as a 
                       JOIN users as b ON b.id = a.user_id WHERE EXTRACT(MONTH FROM a.tgl_po) = ".$bln." AND EXTRACT(YEAR FROM a.tgl_po) = ".$thn." 
                       AND a.is_deleted = 'f' ".$queryRole);
            $totalClose = DB::select("SELECT COUNT(a.id) AS total FROM ".$schema.".expedition_activity as a 
                          JOIN users as b ON b.id = a.user_id 
                          WHERE a.status_activity = 'CLOSED_EXPEDITION' AND EXTRACT(MONTH FROM a.tgl_po) = ".$bln." AND EXTRACT(YEAR FROM a.tgl_po) = ".$thn." 
                          AND a.is_deleted = 'f' ".$queryRole);
            $totalOnProggres = DB::select("SELECT COUNT(a.id) AS total FROM ".$schema.".expedition_activity as a 
                               JOIN users as b ON b.id = a.user_id 
                               WHERE a.status_activity <> 'CLOSED_EXPEDITION' AND EXTRACT(MONTH FROM a.tgl_po) = ".$bln." AND EXTRACT(YEAR FROM a.tgl_po) = ".$thn." 
                               AND a.is_deleted = 'f' ".$queryRole);
            $totalrepair = DB::select("SELECT COUNT(a.id) AS total FROM ".$schema.".stk_repair_header as a
                           JOIN ".$schema.".ex_master_truck as b ON b.id = a.truck_id 
                           WHERE EXTRACT(MONTH FROM a.updated_at) = ".$bln." AND EXTRACT(YEAR FROM a.updated_at) = ".$thn." ".$queryRole);
            $totalrepairBan = DB::select("SELECT COUNT(a.id) AS total FROM ".$schema.".stk_repair_header as a
                              JOIN ".$schema.".ex_master_truck as b ON b.id = a.truck_id 
                              WHERE a.kode_repair LIKE '%RPBAN-%' AND EXTRACT(MONTH FROM a.updated_at) = ".$bln." AND EXTRACT(YEAR FROM a.updated_at) = ".$thn." ".  $queryRole);
            $totalrepairNonBan = DB::select("SELECT COUNT(a.id) AS total FROM ".$schema.".stk_repair_header  as a
                                 JOIN ".$schema.".ex_master_truck as b ON b.id = a.truck_id 
                                 WHERE a.kode_repair LIKE '%RP-%' AND EXTRACT(MONTH FROM a.updated_at) = ".$bln." AND EXTRACT(YEAR FROM a.updated_at) = ".$thn." ". $queryRole);
            $totaltruck = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".ex_master_truck as b WHERE is_deleted = false ".$queryRole);
            $truck = DB::select("SELECT a.cabang_name, COUNT(b.id) FROM ".$schema.".ex_master_truck AS b JOIN ".$schema.".ex_master_cabang AS a ON b.cabang_id = a.id 
                     WHERE b.is_deleted = false ".$queryRole." GROUP BY cabang_id, a.cabang_name");
            $debit = DB::select("SELECT SUM(a.nominal) AS total_income FROM ".$schema.".coa_activity AS a 
                     JOIN ".$schema.".coa_master_sheet AS c ON a.coa_id = c.id 
                     JOIN ".$schema.".expedition_activity AS d ON a.ex_id = d.id
                     JOIN users AS b ON a.created_by = b.id
                     WHERE report_active = 'True' 
                     AND c.jurnal_category = 'DEBIT' AND EXTRACT(MONTH FROM d.tgl_inv) = ".$bln." 
                     AND EXTRACT(YEAR FROM d.tgl_inv) = ".$thn." ".$queryRole);
            $credit = DB::select("SELECT SUM(a.nominal) AS total_income FROM ".$schema.".coa_activity AS a 
                      JOIN ".$schema.".coa_master_sheet AS c ON a.coa_id = c.id
                      JOIN ".$schema.".expedition_activity AS d ON a.ex_id = d.id
                      JOIN users AS b ON a.created_by = b.id
                      WHERE report_active = 'True' 
                      AND c.jurnal_category = 'CREDIT'   AND EXTRACT(MONTH FROM d.tgl_inv) = ".$bln." 
                      AND EXTRACT(YEAR FROM d.tgl_inv) = ".$thn." ".$queryRole);
            $totalIncome = $credit[0]->total_income - $debit[0]->total_income;

            $data['total_expedisi'] = $totalEx[0]->total;
            $data['total_on_progress'] = $totalOnProggres[0]->total;
            $data['total_close'] = $totalClose[0]->total;
            $data['total_repair'] = $totalrepair[0]->total;
            $data['total_repairBan'] = $totalrepairBan[0]->total;
            $data['total_repairNonBan'] = $totalrepairNonBan[0]->total;
            $data['total_truck'] = $totaltruck[0]->total;
            $data['total_income'] = number_format($totalIncome,0,',','.');
            foreach($truck as $key => $val) {
                if($val->cabang_name == 'Cabang TSJ TRUCK') {
                    $data['cabang_tsj'] = $val->count;
                
                } elseif($val->cabang_name == 'Cabang Dawuan FUSO') {
                    $data['cabang_dawuan'] = $val->count;
                }
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
