<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Coa;
use Auth;
use Carbon\Carbon;

class CoaController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'coa_master_jurnal.coa_code, coa_master_jurnal.coa_name, coa_status.param_name, coa_category.param_name, coa_master_jurnal_parent.coa_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $coaList = Coa::leftJoin('all_global_param as coa_status', 'coa_master_jurnal.coa_status', 'coa_status.param_code')
                 ->leftJoin('all_global_param as coa_category', 'coa_master_jurnal.coa_category', 'coa_category.param_code')
                 ->leftJoin('coa_master_jurnal as coa_master_jurnal_parent', 'coa_master_jurnal.coa_parent', 'coa_master_jurnal_parent.id')
                 ->where(function($query) use($whereField, $whereValue) {
                    if($whereValue) {
                        foreach(explode(', ', $whereField) as $idx => $field) {
                        $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                        }
                    }
                 })
                 ->where('coa_status.param_type', 'COA_STATUS')
                 ->where('coa_category.param_type', 'COA_CATEGORY')
                 ->select('coa_master_jurnal.*', 'coa_status.param_name as coa_status_name', 'coa_category.param_name as coa_category_name', 'coa_master_jurnal_parent.coa_name as parent_coa_name')
                 ->orderBy('id', 'ASC')
                 ->paginate();

      foreach($coaList as $row) {
        $row->data_json = $row->toJson();
      }

      if(!isset($coaList)){
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
          'result'=> $coaList
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
      $coa = new Coa;
      
      $this->validate($request, [
        // 'no_coa' => 'required|string|max:255|unique:Coa',
        // 'name' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $coa->{$key} = $row;
      }

      if($coa->save()){
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
      $coa = Coa::find($data['id']);
      
      $this->validate($request, [
        // 'no_coa' => 'required|string|max:255|unique:Coa,no_coa,'.$data['id'].',id',
        // 'name' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $coa->{$key} = $row;
      }

      if($coa->save()){
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
      $coa = Coa::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;
      $coa->deleted_at = $current_date_time;
      $coa->deleted_by = $user_id;
      $coa->is_deleted = true;


      if($coa->save()){
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
