<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\StkGroupSparepart;
use Validator;
use Auth;
use Carbon\Carbon;

class StkGroupSparePartController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'group_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $groupSparepartList = StkGroupSparepart::where(function($query) use($whereField, $whereValue) {
                                    if($whereValue) {
                                      foreach(explode(', ', $whereField) as $idx => $field) {
                                        $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                                      }
                                    }
                                  })
                                ->orderBy('group_name', 'ASC')->get();
      
      foreach($groupSparepartList as $row) {
        $row->data_json = $row->toJson();
      }

      if(!isset($groupSparepartList)){
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
          'data'=> $groupSparepartList
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

  public function getListPagination(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'group_name, all_global_param.param_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $groupSparepartList = StkGroupSparepart::leftJoin('all_global_param', 'stk_master_group_sparepart.group_status', 'all_global_param.id')
                            ->where(function($query) use($whereField, $whereValue) {
                                if($whereValue) {
                                        foreach(explode(', ', $whereField) as $idx => $field) {
                                        $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                                        }
                                    }
                                })
                            ->where('stk_master_group_sparepart.is_deleted', 'false')
                            ->select('stk_master_group_sparepart.*', 'all_global_param.param_name as group_status_name')
                            ->orderBy('group_name', 'ASC')
                            ->paginate();
      
      foreach($groupSparepartList as $row) {
        $row->data_json = $row->toJson();
      }

      if(!isset($groupSparepartList)){
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
          'result'=> $groupSparepartList
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
      $sparePart = new StkGroupSparepart;
      
      $validator = Validator::make($request->all(), [
        // 'no_SparePart' => 'required|string|max:255|unique:SparePart',
        'group_name' => 'required|string|max:255',
      ]);

      if($validator->fails()){
        return response()->json([
          'code' => 400,
          'code_message' => "Kesalahan dalam penginputan / Inputan kosong",
          'code_type' => 'BadRequest',
        ], 400);
      }else{
        unset($data['_token']);
        unset($data['id']);
        $current_date_time = Carbon::now()->toDateTimeString(); 
        $user_id = $request->current_user->id;
        foreach($data as $key => $row) {
          $sparePart->{$key} = $row;
          $sparePart->created_at = $current_date_time;
          $sparePart->created_by = $user_id;
        }

        if($sparePart->save()){
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
      $sparePart = StkGroupSparepart::find($data['id']);
      
      // $this->validate($request, [
      //   // 'no_SparePart' => 'required|string|max:255|unique:SparePart,no_SparePart,'.$data['id'].',id',
      //   'sparepart_name' => 'required|string|max:255',
      // ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = $request->current_user->id;
      foreach($data as $key => $row) {
        $sparePart->{$key} = $row;
        $sparePart->updated_at = $current_date_time;
        $sparePart->updated_by = $user_id;
      }
      if($sparePart->save()){
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
      $sparePart = StkGroupSparepart::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = $request->current_user->id;

      $sparePart->deleted_at = $current_date_time;
      $sparePart->deleted_by = $user_id;
      $sparePart->is_deleted = true;


      if($sparePart->save()){
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
