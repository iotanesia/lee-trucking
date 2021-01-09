<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Group;
use Auth;
use Carbon\Carbon;

class GroupController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'group_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $groupList = Group::where(function($query) use($whereField, $whereValue) {
                     if($whereValue) {
                       foreach(explode(', ', $whereField) as $idx => $field) {
                         $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                       }
                     }
                   })
                   ->where('is_deleted', false)
                   ->select('usr_group.*')
                   ->orderBy('usr_group.id', 'ASC')
                   ->paginate();

      foreach($groupList as $row) {
        $row->data_json = $row->toJson();
      }
      
      if(!isset($groupList)){
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
          'result'=> $groupList
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
      $group = new Group;
      
      $this->validate($request, [
        // 'no_group' => 'required|string|max:255|unique:group',
        // 'group_plat' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $group->{$key} = $row;
      }

      if($group->save()){
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
      $group = Group::find($data['id']);
      
      $this->validate($request, [
        // 'no_group' => 'required|string|max:255|unique:group,no_group,'.$data['id'].',id',
        // 'group_plat' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $group->{$key} = $row;
      }

      if($group->save()){
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
      $group = Group::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;

      $group->deleted_at = $current_date_time;
      $group->deleted_by = $user_id;
      $group->is_deleted = true;


      if($group->save()){
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
