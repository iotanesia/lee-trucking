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

class DaerahController extends Controller
{

    public function getProvinsi(Request $request) {
        if($request->isMethod('GET')) {
            $data = $request->all();
            $whereField = 'provinsi';
            $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
            $provinsiList = Provinsi::where(function($query) use($whereField, $whereValue) {
                                if($whereValue) {
                                    foreach(explode(', ', $whereField) as $idx => $field) {
                                        $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                                    }
                                }
                            })
                            ->orderBy('provinsi', 'ASC')
                            ->get();
            
            return response()->json([
                'code' => 200,
                'code_message' => 'Success',
                'code_type' => 'Success',
                'data'=> $provinsiList
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
