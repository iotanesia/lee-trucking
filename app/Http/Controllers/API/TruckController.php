<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use Auth;

class TruckController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'truck_plat';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $truckList = Truck::join('all_global_param', 'ex_master_truck.truck_status', 'all_global_param.id')
                   ->join('ex_master_cabang','ex_master_truck.cabang_id', 'ex_master_cabang.id')
                   ->where(function($query) use($whereField, $whereValue) {
                     if($whereValue) {
                       foreach(explode(', ', $whereField) as $idx => $field) {
                         $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                       }
                     }
                   })
                   ->select('ex_master_truck.*', 'all_global_param.param_name as status_name', 'ex_master_cabang.cabang_name')
                   ->orderBy('ex_master_truck.id', 'ASC')
                   ->paginate();

      foreach($truckList as $row) {
        $row->data_json = $row->toJson();
      }
      
      if(!isset($truckList)){
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
          'data'=> $truckList
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

  public function add(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $truck = new Truck;
      
      $this->validate($request, [
        // 'no_Truck' => 'required|string|max:255|unique:Truck',
        'truck_plat' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $truck->{$key} = $row;
      }

      if($truck->save()){
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
      $truck = Truck::find($data['id']);
      
      $this->validate($request, [
        // 'no_Truck' => 'required|string|max:255|unique:Truck,no_Truck,'.$data['id'].',id',
        'truck_plat' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $truck->{$key} = $row;
      }

      if($truck->save()){
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
      $truck = Truck::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;

      $truck->deleted_at = $current_date_time;
      $truck->deleted_by = $user_id;
      $truck->is_deleted = true;


      if($truck->save()){
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
}
