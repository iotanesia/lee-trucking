<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Driver;
use App\Models\Kenek;
use App\Models\Truck;
use App\Models\Rekening;
use App\Models\Group;
use App\User;
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
                          ->join('usr_detail', 'usr_detail.id_user', 'ex_master_driver.user_id')
                          ->select('ex_master_driver.*', 'all_global_param.param_name as status_name', 'ex_master_kenek.kenek_name', 'usr_detail.no_rek', 'usr_detail.nama_bank', 'usr_detail.nama_rekening')
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

    
    public function getListRekening(Request $request) {
        if($request->isMethod('GET')) {
            $data = $request->all();
            $rekeningList = Rekening::orderBy('id', 'ASC')
                         ->get();
            
            return response()->json([
                'code' => 200,
                'code_message' => 'Success',
                'code_type' => 'Success',
                'data'=> $rekeningList
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

    public function getListallUser(Request $request) {
        if($request->isMethod('GET')) {
            $data = $request->all();
            $group = false;
            $karyawan = false;
            
            if(isset($data['param_type'])) {
                if($data['param_type'] == 'Karyawan') {
                    $karyawan = true;
                }

                $group = Group::where('group_name', $data['param_type'])->first();
            }

            if($group || $karyawan) {
                $userList = User::select('id', 'name')
                            ->where(function($query) use($karyawan, $group) {
                                if($karyawan) {
                                    $query->where('group_id', '<>', 8)->get();
    
                                } else {
                                    $query->where('group_id', $group->id)->get();
                                }
                            })->get();
                
                return response()->json([
                    'code' => 200,
                    'code_message' => 'Success',
                    'code_type' => 'Success',
                    'data'=> $userList
                ], 200);

            } else {
                return response()->json([
                    'code' => 200,
                    'code_message' => 'Success',
                    'code_type' => 'Success',
                    'data'=> []
                ], 200);
            }

        
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
