<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Kenek;
use Auth;
use Carbon\Carbon;

class KenekController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'kenek_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $kenekList = Kenek::join('all_global_param', 'ex_master_kenek.kenek_status', 'all_global_param.id')
                   ->where(function($query) use($whereField, $whereValue) {
                     if($whereValue) {
                       foreach(explode(', ', $whereField) as $idx => $field) {
                         $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                       }
                     }
                   })
                   ->where('ex_master_kenek.is_deleted', 'false')
                   ->select('ex_master_kenek.*', 'all_global_param.param_name as status_name')
                   ->orderBy('id', 'ASC')
                   ->paginate();
      
      foreach($kenekList as $row) {
        $row->data_json = $row->toJson();
      }

      if(!isset($kenekList)){
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
          'data'=> $kenekList
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
      $kenek = new Kenek;
      
      $this->validate($request, [
        // 'no_kenek' => 'required|string|max:255|unique:kenek',
        'kenek_name' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $kenek->{$key} = $row;
      }

      if($kenek->save()){
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
      $kenek = Kenek::find($data['id']);
      
      $this->validate($request, [
        // 'no_kenek' => 'required|string|max:255|unique:kenek,no_kenek,'.$data['id'].',id',
        'kenek_name' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $kenek->{$key} = $row;
      }

      if($kenek->save()){
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
      $kenek = Kenek::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;
      $kenek->deleted_at = $current_date_time;
      $kenek->deleted_by = $user_id;
      $kenek->is_deleted = true;


      if($kenek->save()){
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
