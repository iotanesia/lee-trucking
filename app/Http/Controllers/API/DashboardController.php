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
            $totalEx = DB::select('SELECT COUNT(id) AS total FROM '.$schema.'.expedition_activity');
            $totalClose = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".expedition_activity WHERE status_activity = 'CLOSED_EXPEDITION'");
            $totalOnProggres = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".expedition_activity WHERE status_activity <> 'CLOSED_EXPEDITION'");
            $totalrepair = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".stk_repair_header");
            $totalrepairBan = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".stk_repair_header WHERE kode_repair LIKE '%RPBAN-%'");
            $totalrepairNonBan = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".stk_repair_header WHERE kode_repair LIKE '%RP-%'");
            $totaltruck = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".ex_master_truck");

            $data['total_expedisi'] = $totalEx[0]->total;
            $data['total_on_progress'] = $totalOnProggres[0]->total;
            $data['total_close'] = $totalClose[0]->total;
            $data['total_repair'] = $totalrepair[0]->total;
            $data['total_repairBan'] = $totalrepairBan[0]->total;
            $data['total_repairNonBan'] = $totalrepairNonBan[0]->total;
            $data['total_truck'] = $totaltruck[0]->total;
            
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

    public function getKabupatenByIdProvinsi(Request $request) {
        if($request->isMethod('GET')) {
        $data = $request->all();
        $kabupatenList = Kabupaten::where('provinsi_id', $data['idProvinsi'])
                            ->orderBy('kabupaten', 'ASC')
                            ->get();
        
        return response()->json([
            'code' => 200,
            'code_message' => 'Success',
            'code_type' => 'Success',
            'data'=> $kabupatenList
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

    public function getKecamatanByIdKabupaten(Request $request) {
        if($request->isMethod('GET')) {
        $data = $request->all();
        $kecamatanList = Kecamatan::where('kabupaten_id', $data['idKabupaten'])
                            ->orderBy('kecamatan', 'ASC')
                            ->get();
        
        return response()->json([
            'code' => 200,
            'code_message' => 'Success',
            'code_type' => 'Success',
            'data'=> $kecamatanList
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
