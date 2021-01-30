<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\StkRepairHeader;
use Auth;
use Carbon\Carbon;

class StkRepairHeaderController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'name, no_StkRepairHeader';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $stkRepairHeader = StkRepairHeader::join('ex_master_truck', 'stk_repair_header.truck_id', 'ex_master_truck.id')
                        // ->join('ex_master_driver', 'ex_master_truck.id', 'ex_master_driver.truck_id')
                        ->where(function($query) use($whereField, $whereValue) {
                            if($whereValue) {
                                foreach(explode(', ', $whereField) as $idx => $field) {
                                    $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                                }
                            }
                        })
                        ->select('stk_repair_header.*', 'ex_master_truck.truck_plat', 'ex_master_truck.truck_name')
                        ->orderBy('id', 'ASC')
                        ->paginate();
      
      foreach($stkRepairHeader as $row) {
        $row->data_json = $row->toJson();
      }
      
      if(!isset($stkRepairHeader)){
        return response()->json([
          'code' => 404,
          'code_message' => 'Data tidak ditemukan',
          'code_type' => 'BadRequest',
          'result'=> null
        ], 404);
      }else{
        return response()->json([
          'code' => 200,
          'code_message' => 'Success',
          'code_type' => 'Success',
          'result'=> $stkRepairHeader
        ], 200);
      }
      
      
    } else {
      return response()->json([
        'code' => 405,
        'code_message' => 'Method salah',
        'code_type' => 'BadRequest',
        'result'=> null
      ], 405);
    }
  }

  public function add(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $stkRepairHeader = new StkRepairHeader;
      
      $this->validate($request, [
        // 'no_StkRepairHeader' => 'required|string|max:255|unique:StkRepairHeader',
        // 'name' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $stkRepairHeader->{$key} = $row;
      }

      if($stkRepairHeader->save()){
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
      dd($data);
      $stkRepairHeader = StkRepairHeader::find($data['id']);
      
      $this->validate($request, [
        // 'no_StkRepairHeader' => 'required|string|max:255|unique:StkRepairHeader,no_StkRepairHeader,'.$data['id'].',id',
        // 'name' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $stkRepairHeader->{$key} = $row;
      }

      if($stkRepairHeader->save()){
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
      $stkRepairHeader = StkRepairHeader::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;
      $stkRepairHeader->deleted_at = $current_date_time;
      $stkRepairHeader->deleted_by = $user_id;
      $stkRepairHeader->is_deleted = true;


      if($stkRepairHeader->save()){
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
