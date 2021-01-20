<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\ExpeditionActivity;
use Auth;
use Carbon\Carbon;
use DB;

class BonusDriverRitController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'name, no_Reward';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $rewardList = ExpeditionActivity::join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                    ->where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                            foreach(explode(', ', $whereField) as $idx => $field) {
                                $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                            }
                        }
                    })
                    ->select('driver_id', 'driver_name', DB::raw('COUNT("driver_id") AS total_rit'))
                    ->groupBy('driver_id', 'driver_name')
                    ->orderBy('total_rit', 'DESC')
                    ->paginate();
      
      foreach($rewardList as $row) {
        $row->data_json = $row->toJson();
      }
      
      if(!isset($rewardList)){
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
          'result'=> $rewardList
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

  public function getListReward(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'name, no_Reward';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $rewardList = ExpeditionActivity::join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                    ->where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                            foreach(explode(', ', $whereField) as $idx => $field) {
                                $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                            }
                        }
                    })
                    ->select('driver_id', 'driver_name', DB::raw('COUNT("driver_id") AS total_rit'))
                    ->groupBy('driver_id', 'driver_name')
                    ->orderBy('total_rit', 'DESC')
                    ->paginate();
      
      foreach($rewardList as $row) {
        $row->data_json = $row->toJson();
      }
      
      if(!isset($rewardList)){
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
          'result'=> $rewardList
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
      $reward = new Reward;
      
      $this->validate($request, [
        // 'no_Reward' => 'required|string|max:255|unique:Reward',
        // 'name' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $reward->{$key} = $row;
      }

      if($reward->save()){
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
      $reward = Reward::find($data['id']);
      
      $this->validate($request, [
        // 'no_Reward' => 'required|string|max:255|unique:Reward,no_Reward,'.$data['id'].',id',
        // 'name' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $reward->{$key} = $row;
      }

      if($reward->save()){
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
      $reward = Reward::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;
      $reward->deleted_at = $current_date_time;
      $reward->deleted_by = $user_id;
      $reward->is_deleted = true;


      if($reward->save()){
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
