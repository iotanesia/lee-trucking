<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Cabang;
use Auth;
use Carbon\Carbon;

class CabangController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'cabang_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $cabangList = Cabang::where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                          foreach(explode(', ', $whereField) as $idx => $field) {
                            $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                          }
                        }
                      })
                      ->orderBy('id', 'ASC')
                      ->get();

      foreach($cabangList as $row) {
        $row->data_json = $row->toJson();
      }
      
      if(!isset($cabangList)){
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
          'data'=> $cabangList
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
      $cabang = new Cabang;
      
      $this->validate($request, [
        // 'no_cabang' => 'required|string|max:255|unique:cabang',
        'nomor_inv' => 'required|string|max:255|unique:'.$request->current_user->schema.'.expedition_activity',
        'cabang_name' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = $request->current_user->id;
      foreach($data as $key => $row) {
        $cabang->{$key} = $row;
        $cabang->created_at = $current_date_time;
        $cabang->created_by = $user_id;
      }

      if($cabang->save()){
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
      $cabang = cabang::find($data['id']);
      
      $this->validate($request, [
        // 'no_cabang' => 'required|string|max:255|unique:cabang,no_cabang,'.$data['id'].',id',
        'cabang_name' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = $request->current_user->id;
      foreach($data as $key => $row) {
        $cabang->{$key} = $row;
        $cabang->updated_at = $current_date_time;
        $cabang->updated_by = $user_id;
      }

      if($cabang->save()){
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
      $cabang = cabang::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = $request->current_user->id;
      $cabang->deleted_at = $current_date_time;
      $cabang->deleted_by = $user_id;
      $cabang->is_deleted = true;


      if($cabang->save()){
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
