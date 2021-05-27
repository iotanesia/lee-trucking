<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\Ban;
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
          
        if($data['jumlah_ban']) {
            for ($i=0; $i <= $data['jumlah_ban']; $i++) { 
                $ban = new Ban;
                $no = $i + 1;
                $ban->name_ban = 'Ban-'.$no;
                $ban->code_ban = 'BAN-'.$truck_id.'-'.$no;
                $ban->truck_id = $truck->id;
                $ban->save();
            }
        }

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
        if($data['jumlah_ban']) {
            $bans = Ban::where('truck_id', $request->id)->first();

            if(!$bans) {
                for ($i=0; $i <= $data['jumlah_ban']; $i++) { 
                    $ban = new Ban;
                    $no = $i + 1;
                    $ban->name_ban = 'Ban-'.$no;
                    $ban->code_ban = 'BAN-'.$request->id.'-'.$no;
                    $ban->truck_id = $truck->id;
                    $ban->save();
                }
            }
        }

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
}
