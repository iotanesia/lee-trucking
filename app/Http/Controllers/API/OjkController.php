<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Ojk;
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
                 ->where(function($query) use($whereField, $whereValue) {
                   if($whereValue) {
                     foreach(explode(', ', $whereField) as $idx => $field) {
                       $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                     }
                   }
                 })
                 ->select('ex_master_ojk.*', 'ex_wil_kabupaten.kabupaten', 'ex_wil_kecamatan.kecamatan', 'ex_wil_provinsi.provinsi', 'ex_master_cabang.cabang_name')
                 ->orderBy('id', 'ASC')
                 ->paginate();
      
      foreach($ojkList as $row) {
        $row->data_json = $row->toJson();
      }

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
      
      $this->validate($request, [
        'kabupaten_id' => 'required',
        'kecamatan_id' => 'required',
        'harga_ojk' => 'required',
        'harga_otv' => 'required',
        'cabang_id' => 'required',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $ojk->{$key} = $row;
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
      
      $this->validate($request, [
        'kabupaten_id' => 'required',
        'kecamatan_id' => 'required',
        'harga_ojk' => 'required',
        'harga_otv' => 'required',
        'cabang_id' => 'required',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $ojk->{$key} = $row;
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

      if($ojk->delete()){
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
