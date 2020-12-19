<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Ojk;
use Carbon\Carbon;
use Validator;
use Auth;

class OjkController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'cabang_name, kabupaten, kecamatan, provinsi';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $ojkList = Ojk::join('ex_master_cabang', 'ex_master_cabang.id', 'ex_master_ojk.cabang_id')
                 ->leftJoin('ex_wil_kabupaten', 'ex_wil_kabupaten.id', 'ex_master_ojk.kabupaten_id')
                 ->leftJoin('ex_wil_kecamatan', 'ex_wil_kecamatan.id', 'ex_master_ojk.kecamatan_id')
                 ->leftJoin('ex_wil_provinsi', 'ex_wil_provinsi.id', 'ex_master_ojk.provinsi_id')
                 ->where('ex_master_ojk.is_deleted','=','false')
                 ->where(function($query) use($whereField, $whereValue) {
                   if($whereValue) {
                     foreach(explode(', ', $whereField) as $idx => $field) {
                       $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                     }
                   }
                 })
                 ->select('ex_master_ojk.*', 'ex_wil_kabupaten.kabupaten', 'ex_wil_kecamatan.kecamatan', 'ex_wil_provinsi.provinsi', 'ex_master_cabang.cabang_name')
                 ->orderBy('id', 'ASC')
                 ->paginate();
      
      foreach($ojkList as $row) {
          $row->data_json = $row->toJson();
          $row->harga_ojk = number_format($row->harga_ojk);
          $row->harga_otv = number_format($row->harga_otv);
      }
// dd($ojkList);
      if(!isset($ojkList)){
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
          'result'=> $ojkList
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
      $ojk = new Ojk;
      
      $validator = Validator::make($request->all(), [
        'provinsi_id' => 'required',
        'kabupaten_id' => 'required',
        'kecamatan_id' => 'required',
        'harga_ojk' => 'required',
        'harga_otv' => 'required',
        'cabang_id' => 'required',
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
        $user_id = Auth::user()->id;
        foreach($data as $key => $row) {
          $ojk->{$key} = $row;
          $ojk->created_at = $current_date_time; 
          $ojk->created_by = $user_id; 
        }

        if($ojk->save()){
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
      $ojk = Ojk::find($data['id']);
      
      $validator = Validator::make($request->all(), [
        'provinsi_id' => 'required',
        'kabupaten_id' => 'required',
        'kecamatan_id' => 'required',
        'harga_ojk' => 'required',
        'harga_otv' => 'required',
        'cabang_id' => 'required',
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
        $user_id = Auth::user()->id;
        foreach($data as $key => $row) {
          $ojk->{$key} = $row;
          $ojk->updated_at = $current_date_time; 
          $ojk->updated_by = $user_id; 
        }

        if($ojk->save()){
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

  public function delete(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $ojk = Ojk::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;

      $ojk->deleted_at = $current_date_time;
      $ojk->deleted_by = $user_id;
      $ojk->is_deleted = true;


      if($ojk->save()){
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
