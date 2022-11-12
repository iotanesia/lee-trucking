<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Driver;
use App\Models\UserDetail;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'driver_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $driverList = Driver::join('all_global_param', 'ex_master_driver.driver_status', 'all_global_param.id')
                    ->leftJoin('ex_master_kenek', 'ex_master_driver.kenek_id', 'ex_master_kenek.id')
                    ->where(function($query) use($whereField, $whereValue) {
                      if($whereValue) {
                        foreach(explode(', ', $whereField) as $idx => $field) {
                          $query->orWhere($field, 'ILIKE', "%".$whereValue."%");
                        }
                      }
                    })
                    ->where('ex_master_driver.is_deleted', 'false')
                    ->select('ex_master_driver.*', 'all_global_param.param_name as status_name', 'ex_master_kenek.kenek_name')
                    ->orderBy('id', 'ASC')
                    ->paginate();
      
      foreach($driverList as $row) {
        $row->data_json = $row->toJson();
      }

      if(!isset($driverList)){
        return response()->json([
          'code' => 404,
          'code_message' => 'Data tidak ditemukan',
          'code_type' => 'BadRequest',
          'data'=> null
        ], 404);
      }else{
        return response()->json([
          'code' => 200,
          'code_message' => 'Success',
          'code_type' => 'Success',
          'data'=> $driverList
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

  public function detailDriver(Request $request) {
    if($request->isMethod('GET')) {
        $data = $request->all();
        $driverList = Driver::join('all_global_param', 'ex_master_driver.driver_status', 'all_global_param.id')
                      ->leftJoin('ex_master_kenek', 'ex_master_driver.kenek_id', 'ex_master_kenek.id')
                      ->join('usr_detail', 'usr_detail.id_user', 'ex_master_driver.user_id')
                      ->select('ex_master_driver.*', 'all_global_param.param_name as status_name', 'ex_master_kenek.kenek_name', 'usr_detail.no_rek', 'usr_detail.nama_bank', 'usr_detail.nama_rekening', DB::raw('CAST(usr_detail.nomor_hp AS  VARCHAR)'))
                      ->where('ex_master_driver.id', $request->id)
                      ->first();
        
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

  public function add(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $driver = new Driver;
      
      $this->validate($request, [
        // 'no_Driver' => 'required|string|max:255|unique:Driver',
        'user_id' => 'required|string|max:255|unique:'.$request->current_user->schema.'.ex_master_driver',
        'driver_name' => 'required|string|max:255',
        'kenek_id' => 'required',
        'driver_status' => 'required',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $driver->{$key} = $row;
      }

      if($driver->save()){
        return response()->json([
          'code' => 200,
          'code_message' => 'Berhasil menyimpan data',
          'code_type' => 'Success',
        ], 200);
      
      } else {
        return response()->json([
          'code' => 401,
          'code_message' => 'Gagal menyimpan data',
          'code_type' => 'BadRequest',
        ], 401);
      }
      
    } else {
      return response()->json([
        'code' => 405,
        'code_message' => 'Method salah',
        'code_type' => 'BadRequest',
      ], 405);
    }
  }

  public function edit(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $driver = Driver::find($data['id']);
      
      $this->validate($request, [
        // 'no_Driver' => 'required|string|max:255|unique:Driver,no_Driver,'.$data['id'].',id',
        'user_id' => 'required|string|max:255|unique:'.$request->current_user->schema.'.ex_master_driver,user_id,'.$data['id'].',id',
        'driver_name' => 'required|string|max:255',
        'kenek_id' => 'required',
        'driver_status' => 'required',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $driver->{$key} = $row;
      }

      if($driver->save()){
        return response()->json([
          'code' => 200,
          'code_message' => 'Berhasil menyimpan data',
          'code_type' => 'Success',
        ], 200);
      
      } else {
        return response()->json([
          'code' => 401,
          'code_message' => 'Gagal menyimpan data',
          'code_type' => 'BadRequest',
        ], 401);
      }
      
    } else {
      return response()->json([
        'code' => 405,
        'code_message' => 'Method salah',
        'code_type' => 'BadRequest',
      ], 405);
    }
  }

  public function delete(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $driver = Driver::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = $request->current_user->id;
      $driver->deleted_at = $current_date_time;
      $driver->deleted_by = $user_id;
      $driver->is_deleted = true;


      if($driver->save()){
        return response()->json([
          'code' => 200,
          'code_message' => 'Berhasil menghapus data',
          'code_type' => 'Success',
        ], 200);
      
      } else {
        return response()->json([
          'code' => 401,
          'code_message' => 'Gagal menghapus data',
          'code_type' => 'BadRequest',
        ], 401);
      }
      
    } else {
      return response()->json([
        'code' => 405,
        'code_message' => 'Method salah',
        'code_type' => 'BadRequest',
      ], 405);
    }
  }
  
  public function getUserDriverList(Request $request){
    if($request->isMethod('GET')) {
      $userList = UserDetail::leftjoin('public.users', 'public.users.id', 'usr_detail.id_user')
                       ->where('public.users.group_id', 14)
                       ->select('usr_detail.*')
                       ->orderBy('usr_detail.first_name', 'ASC')
                       ->get();
      
      foreach($userList as $row) {
        $row->data_json = $row->toJson();
      }
      
      if(!isset($userList)){
        return response()->json([
          'code' => 404,
          'code_message' => 'Data tidak ditemukan',
          'code_type' => 'BadRequest',
          'data'=> null
        ], 404);
      }else{
        return response()->json([
          'code' => 200,
          'code_message' => 'Success',
          'code_type' => 'Success',
          'data'=> $userList
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
