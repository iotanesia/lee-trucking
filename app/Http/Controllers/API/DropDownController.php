<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Driver;
use App\Models\Kenek;
use App\Models\Truck;
use DB;
use Validator;

class DropDownController extends Controller
{

    public function getListTruck(Request $request) {
        if($request->isMethod('GET')) {
            $data = $request->all();
            $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
            $truckList = Truck::join('all_global_param', 'ex_master_truck.truck_status', 'all_global_param.id')
                         ->join('ex_master_cabang','ex_master_truck.cabang_id', 'ex_master_cabang.id')
                         ->select('ex_master_truck.*', 'all_global_param.param_name as status_name', 'ex_master_cabang.cabang_name')
                         ->orderBy('ex_master_truck.id', 'ASC')
                         ->get();
            
            return response()->json([
                'code' => 200,
                'code_message' => 'Success',
                'code_type' => 'Success',
                'data'=> $truckList
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

    public function getListDriver(Request $request) {
        if($request->isMethod('GET')) {
            $data = $request->all();
            $driverList = Driver::join('all_global_param', 'ex_master_driver.driver_status', 'all_global_param.id')
                          ->leftJoin('ex_master_kenek', 'ex_master_driver.kenek_id', 'ex_master_kenek.id')
                          ->select('ex_master_driver.*', 'all_global_param.param_name as status_name', 'ex_master_kenek.kenek_name')
                          ->orderBy('id', 'ASC')
                          ->get();
            
            return response()->json([
                'code' => 200,
                'code_message' => 'Success',
                'code_type' => 'Success',
                'data'=> $driverList
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

    public function getListKenek(Request $request) {
        if($request->isMethod('GET')) {
            $data = $request->all();
            $kenekList = Kenek::join('all_global_param', 'ex_master_kenek.kenek_status', 'all_global_param.id')
                         ->select('ex_master_kenek.*', 'all_global_param.param_name as status_name')
                         ->orderBy('id', 'ASC')
                         ->get();
            
            return response()->json([
                'code' => 200,
                'code_message' => 'Success',
                'code_type' => 'Success',
                'data'=> $kenekList
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