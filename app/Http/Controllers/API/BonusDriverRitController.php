<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\ExpeditionActivity;
use App\Models\Reward;
use Auth;
use Carbon\Carbon;
use DB;

class BonusDriverRitController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $month = '01';
      $year = '2021';
      $firstDate = date('Y-m-01', strtotime($year.'-'.$month.'-01'));
      $lastDate = date('Y-m-t', strtotime($year.'-'.$month.'-01'));
      $firstDate = date('Y-m-01');
      $lastDate = date('Y-m-t');
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
                    ->where('status_activity', 'CLOSED_EXPEDITION')
                    ->whereRaw("expedition_activity.updated_at between CAST('".$firstDate." 00:00:00' AS DATE) AND CAST('".$lastDate." 23:59:59' AS DATE)")
                    ->select('driver_id', 'driver_name', DB::raw('COUNT("driver_id") AS total_rit'))
                    ->groupBy('driver_id', 'driver_name')
                    ->orderBy('total_rit', 'DESC')
                    ->paginate();
      
      foreach($rewardList as $row) {
          $reward = Reward::where('min', '<=', $row->total_rit)->where('max', '>=', $row->total_rit)->orderBy('min', 'DESC')->first();
          $row->reward_jenis = $reward ? $reward->reward_jenis : '-';
          $row->bonus = $reward ? $reward->bonus : 0;
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
      $whereField = 'ex_master_driver.driver_name, no_Reward';
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

  public function getListByPeriode(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $firstDate = date('Y-m-01');
      $lastDate = date('Y-m-t');
      $user = Auth::user();
      $whereField = 'name, no_Reward';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $rewardList = ExpeditionActivity::join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                    ->where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                            foreach(explode(', ', $whereField) as $idx => $field) {
                                $query->orWhere($field, '=', $whereValue);
                            }
                        }
                    })
                    ->where('ex_master_driver.user_id', $user->id)
                    ->where('expedition_activity.status_activity','CLOSED_EXPEDITION')
                    ->whereYear('expedition_activity.updated_at', $data['year'])
                    ->whereMonth('expedition_activity.updated_at', $data['month'])
                    ->select('driver_id', 'driver_name', DB::raw('COUNT("driver_id") AS total_rit'))
                    ->whereNotNull('driver_id')
                    ->groupBy('driver_id', 'driver_name')
                    ->orderBy('total_rit', 'DESC')->first();
      
      if(!isset($rewardList)){
        return response()->json([
          'code' => 404,
          'code_message' => 'Data tidak ditemukan',
          'code_type' => 'BadRequest',
          'data'=> null
        ], 404);
      }else{
        $reward = Reward::where('min', '<=', $rewardList->total_rit)->where('max', '>=', $rewardList->total_rit)->orderBy('min', 'DESC')->first();
        $rewardList->reward_jenis = $reward ? $reward->reward_jenis : '-';
        $rewardList->bonus = $reward ? $reward->bonus : 0;
        $rewardList->data_json = $rewardList->toJson();
        return response()->json([
          'code' => 200,
          'code_message' => 'Success',
          'code_type' => 'Success',
          'data'=> $rewardList
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

  public function getListByTujuan(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $firstDate = date('Y-m-01');
      $lastDate = date('Y-m-t');
      $user = Auth::user();
      $whereField = 'name, no_Reward';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $rewardList = ExpeditionActivity::join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                    ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                    ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                    ->join('ex_wil_provinsi', 'ex_master_ojk.provinsi_id', 'ex_wil_provinsi.id')
                    ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                    ->where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                            foreach(explode(', ', $whereField) as $idx => $field) {
                                $query->orWhere($field, '=', $whereValue);
                            }
                        }
                    })
                    ->where('expedition_activity.status_activity','CLOSED_EXPEDITION')
                    ->whereYear('expedition_activity.updated_at', $data['year'])
                    ->whereMonth('expedition_activity.updated_at', $data['month'])
                    ->whereNotNull('ojk_id')
                    ->select('ojk_id', 'kabupaten', 'kecamatan', 'provinsi', 'cabang_name', DB::raw('COUNT("ojk_id") AS total_rit'))
                    ->groupBy('ojk_id', 'kabupaten', 'kecamatan', 'provinsi', 'cabang_name')
                    ->paginate();
      
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

  public function getListByTujuanDetail(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $firstDate = date('Y-m-01');
      $lastDate = date('Y-m-t');
      $user = Auth::user();
      $whereField = 'name, no_Reward';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $rewardList = ExpeditionActivity::join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                    ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                    ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                    ->join('ex_wil_provinsi', 'ex_master_ojk.provinsi_id', 'ex_wil_provinsi.id')
                    ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                    ->join('ex_master_truck', 'ex_master_truck.id', 'expedition_activity.truck_id')
                    ->join('ex_master_driver', 'ex_master_driver.id', 'expedition_activity.driver_id')
                    ->where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                            foreach(explode(', ', $whereField) as $idx => $field) {
                                $query->orWhere($field, '=', $whereValue);
                            }
                        }
                    })
                    ->where('expedition_activity.ojk_id', $data['ojk_id'])
                    ->where('expedition_activity.status_activity','CLOSED_EXPEDITION')
                    ->whereYear('expedition_activity.updated_at', $data['year'])
                    ->whereMonth('expedition_activity.updated_at', $data['month'])
                    ->whereNotNull('ojk_id')
                    ->select('expedition_activity.*', 'kabupaten', 'kecamatan', 'provinsi', 'cabang_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_name', 'ex_master_truck.truck_plat')
                    ->paginate();
      
      if(!isset($rewardList)){
        return response()->json([
          'code' => 404,
          'code_message' => 'Data tidak ditemukan',
          'code_type' => 'BadRequest',
          'result'=> null
        ], 404);

      }else{
        foreach($rewardList as $row) {
            $row->data_json = $row->toJson();
            return response()->json([
            'code' => 200,
            'code_message' => 'Success',
            'code_type' => 'Success',
            'result'=> $rewardList
            ], 200);
        }
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

  public function getKenekBonusListByPeriode(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $firstDate = date('Y-m-01');
      $lastDate = date('Y-m-t');
      $user = Auth::user();
      $whereField = 'name, no_Reward';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $rewardList = ExpeditionActivity::join('ex_master_kenek', 'expedition_activity.kenek_id', 'ex_master_kenek.id')
                    ->where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                            foreach(explode(', ', $whereField) as $idx => $field) {
                                $query->orWhere($field, '=', $whereValue);
                            }
                        }
                    })
                    ->where('expedition_activity.status_activity','CLOSED_EXPEDITION')
                    ->whereYear('expedition_activity.updated_at', $data['year'])
                    ->whereMonth('expedition_activity.updated_at', $data['month'])
                    ->select('kenek_id', 'kenek_name', DB::raw('COUNT("kenek_id") AS total_rit'))
                    ->whereNotNull('driver_id')
                    ->groupBy('kenek_id', 'kenek_name')
                    ->orderBy('total_rit', 'DESC')->paginate();
      
      
      if(!isset($rewardList)){
        return response()->json([
          'code' => 404,
          'code_message' => 'Data tidak ditemukan',
          'code_type' => 'BadRequest',
          'result'=> null
        ], 404);
      }else{
        foreach($rewardList as $row) {
            $reward = Reward::where('min', '<=', $row->total_rit)->where('max', '>=', $row->total_rit)->orderBy('min', 'DESC')->first();
            $row->reward_jenis = $reward ? $reward->reward_jenis : '-';
            $row->bonus = $reward ? $reward->bonus : 0;
            $row->data_json = $row->toJson();
        }
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
}
