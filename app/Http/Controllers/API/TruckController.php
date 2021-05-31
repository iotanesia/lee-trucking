<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\Ban;
use App\Models\HistoryBan;
use Auth;
use Carbon\Carbon;
use DB;

class TruckController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'truck_plat, truck_name, all_global_param.param_name, ex_master_cabang.cabang_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $truckList = Truck::join('all_global_param', 'ex_master_truck.truck_status', 'all_global_param.id')
                   ->join('ex_master_cabang','ex_master_truck.cabang_id', 'ex_master_cabang.id')
                   ->with(['ban'])
                   ->where(function($query) use($whereField, $whereValue) {
                     if($whereValue) {
                       foreach(explode(', ', $whereField) as $idx => $field) {
                         $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                       }
                     }
                   })
                   ->where('ex_master_truck.is_deleted', 'f')
                   ->select('ex_master_truck.*', 'all_global_param.param_name as status_name', 'ex_master_cabang.cabang_name')
                   ->orderBy('ex_master_truck.id', 'ASC')
                   ->paginate();

      foreach($truckList as $row) {
        $row->data_json = $row->toJson();
        $row->ban_json = $row->ban->toJson();
      }
      
      if(!isset($truckList)){
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
          'data'=> $truckList
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
      $truck = new Truck;
      
      $this->validate($request, [
        'truck_name' => 'required|string|max:255',
        'truck_plat' => 'required|string|max:255',
        'truck_corporate_asal' => 'required',
        'truck_status' => 'required',
        'driver_id' => 'required',
        'cabang_id' => 'required',
      ]);

      unset($data['_token']);
      unset($data['id']);
      
      if(isset($data['truck_date_join'])) {
          $data['truck_date_join'] = date('Y-m-d', strtotime($data['truck_date_join']));
      }

      foreach($data as $key => $row) {
        $truck->{$key} = $row;
      }

      if($truck->save()){

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
      $truck = Truck::find($data['id']);
      
      $this->validate($request, [
        'truck_name' => 'required|string|max:255',
        'truck_plat' => 'required|string|max:255',
        'truck_corporate_asal' => 'required',
        'truck_status' => 'required',
        'driver_id' => 'required',
        'truck_date_join' => 'required',
        'cabang_id' => 'required',
      ]);
      
      unset($data['_token']);
      unset($data['id']);

      if(isset($data['truck_date_join'])) {
        $data['truck_date_join'] = date('Y-m-d', strtotime($data['truck_date_join']));
      }
      
      foreach($data as $key => $row) {
        $truck->{$key} = $row;
      }

      if($truck->save()){
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
      $truck = Truck::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;

      $truck->deleted_at = $current_date_time;
      $truck->deleted_by = $user_id;
      $truck->is_deleted = true;


      if($truck->save()){
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

  public function addBan(Request $request) {
    $data = $request->all();

    foreach ($data['ban']['ban_name'] as $key => $value) {
        $ban = new Ban;
        $ban->truck_id = $data['id'];
        $ban->name_ban = $value;
        $ban->desc = $data['ban']['description'][$key];
        $ban->code_ban = 'BN0'.$data['id'].date('dmyHis');
        $ban->save();
    }

    return response()->json([
        'code' => 200,
        'code_message' => 'Berhasil menyimpan data',
        'code_type' => 'Success',
      ], 200);
  }

  public function addBanRepair(Request $request) {
    $data = $request->all();
    $oldBan = Ban::find($data['id']);
    // dd($data);

    $history = new HistoryBan;
    $history->ban_id = $oldBan->id;
    $history->total_ritasi = $oldBan->total_ritasi;
    $history->batas_ritasi = $oldBan->batas_ritasi;
    $history->truck_id = $oldBan->truck_id;
    $history->save();

    $gapRitasi = $oldBan->batas_ritasi - $oldBan->total_ritasi;
    $oldBan->batas_ritasi = 200 + $gapRitasi;
    $oldBan->total_ritasi = 0;
    $oldBan->name_ban = $data['name_ban'];
    $oldBan->desc = $data['description'];
    $oldBan->save();

    return response()->json([
        'code' => 200,
        'code_message' => 'Berhasil menyimpan data',
        'code_type' => 'Success',
      ], 200);
  }

  public function Bandeleted(Request $request) {
    if($request->isMethod('POST')) {
        $data = $request->all();
        $ban = Ban::find($data['id']);
        $current_date_time = Carbon::now()->toDateTimeString(); 
        $user_id = Auth::user()->id;
        $ban->deleted_at = $current_date_time;
        
        if($ban->save()){
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
