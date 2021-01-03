<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\ExpeditionActivity;
use App\Models\ExStatusActivity;
use App\Models\Ojk;
use App\Models\Kenek;
use App\Models\Driver;
use Auth;
use DB;
use Carbon\Carbon;

class ExpeditionController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'ExpeditionActivity_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $expeditionActivityList = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
                   ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
                   ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                   ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                   ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                   ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                   ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                   ->leftJoin('ex_master_kenek','expedition_activity.kenek_id', 'ex_master_kenek.id')
                   ->where(function($query) use($whereField, $whereValue) {
                     if($whereValue) {
                       foreach(explode(', ', $whereField) as $idx => $field) {
                         $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                       }
                     }
                   })
                   ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat', 
                            'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name', 'ex_master_ojk.harga_ojk', 'ex_master_ojk.harga_otv', 'ex_master_kenek.kenek_name')
                   ->orderBy('id', 'ASC')
                   ->paginate();
      
      foreach($expeditionActivityList as $row) {
        $row->data_json = $row->toJson();
      }

      if(!isset($expeditionActivityList)){
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
          'result'=> $expeditionActivityList
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

  public function getListApprovalOjk(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'kabupaten, kecamatan, cabang_name, all_global_param.param_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $expeditionActivityList = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
                   ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
                   ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                   ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                   ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                   ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                   ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                   ->whereIn('expedition_activity.status_activity', ['SUBMIT', 'APPROVAL_OJK_DRIVER', 'DRIVER_MENUJU_TUJUAN', 'DRIVER_SAMPAI_TUJUAN', 'DRIVER_SELESAI_EKSPEDISI'])
                   ->where(function($query) use($whereField, $whereValue) {
                     if($whereValue) {
                       foreach(explode(', ', $whereField) as $idx => $field) {
                         $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                       }
                     }
                   })
                   ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat', 
                            'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name', 'ex_master_ojk.harga_ojk', 'ex_master_ojk.harga_otv')
                   ->orderBy('id', 'ASC')
                   ->paginate();
      
      foreach($expeditionActivityList as $row) {
        $row->data_json = $row->toJson();
      }

      if(!isset($expeditionActivityList)){
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
          'result'=> $expeditionActivityList
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

  public function getListApprovalOtv(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'kabupaten, kecamatan, cabang_name, all_global_param.param_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $expeditionActivityList = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
                   ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
                   ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                   ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                   ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                   ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                   ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                   ->whereNotIn('expedition_activity.status_activity', ['SUBMIT', 'APPROVAL_OJK_DRIVER', 'DRIVER_MENUJU_TUJUAN', 'DRIVER_SAMPAI_TUJUAN', 'DRIVER_SELESAI_EKSPEDISI'])
                   ->where(function($query) use($whereField, $whereValue) {
                     if($whereValue) {
                       foreach(explode(', ', $whereField) as $idx => $field) {
                         $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                       }
                     }
                   })
                   ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat', 
                            'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name', 'ex_master_ojk.harga_ojk', 'ex_master_ojk.harga_otv')
                   ->orderBy('id', 'ASC')
                   ->paginate();
      
      foreach($expeditionActivityList as $row) {
        $row->data_json = $row->toJson();
      }

      if(!isset($expeditionActivityList)){
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
          'result'=> $expeditionActivityList
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
      $idUser = Auth::user()->id;
      $current_date_time = Carbon::now()->toDateTimeString(); 
      DB::connection(Auth::user()->schema)->beginTransaction();
      $expeditionActivity = new ExpeditionActivity;
      
      $this->validate($request, [
        // 'no_ExpeditionActivity' => 'required|string|max:255|unique:ExpeditionActivity',
        // 'ExpeditionActivity_name' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);
      unset($data['jenis_surat_jalan']);

      foreach($data as $key => $row) {
        $expeditionActivity->{$key} = $row;
        $expeditionActivity->status_activity = 'SUBMIT';
      }

      $expeditionActivity->created_at = $current_date_time;
      $expeditionActivity->created_by = $idUser;
      $expeditionActivity->user_id = $idUser;

      if($expeditionActivity->save()) { 
        $code = str_repeat("0", 4 - strlen($expeditionActivity->id)).$expeditionActivity->id;
        $codes = $request->jenis_surat_jalan.date('Y').$code;

        $expeditionActivity->nomor_surat_jalan = $codes;
        $expeditionActivity->save();

        DB::connection(Auth::user()->schema)->commit();
        return response()->json([
          'code' => 200,
          'code_message' => 'Berhasil menyimpan data',
          'code_type' => 'Success',
        ], 200);
      
      } else {
        DB::connection(Auth::user()->schema)->rollback();
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
      $expeditionActivity = ExpeditionActivity::find($data['id']);
      $idUser = Auth::user()->id;

      $current_date_time = Carbon::now()->toDateTimeString(); 

      $statusActivityParam = $request->update_lates_status;

      $this->validate($request, [
        // 'no_ExpeditionActivity' => 'required|string|max:255|unique:ExpeditionActivity,no_ExpeditionActivity,'.$data['id'].',id',
        // 'ExpeditionActivity_name' => 'required|string|max:255',

      ]);
      
      unset($data['_token']);
      unset($data['id']);
      unset($data['jenis_surat_jalan']);
      
      $expeditionActivity->updated_by = $idUser;
      $expeditionActivity->updated_at = $current_date_time;

      if($statusActivityParam){
        $img = $request->file('img');
        $exStatusActivity = new ExStatusActivity();

        unset($data['update_lates_status']);

        foreach($data as $key => $row) {
          $exStatusActivity->{$key} = $row;
        }

        $expeditionActivity->status_activity = $request->status_activity;
        $exStatusActivity->approval_by = $idUser;
        $exStatusActivity->approval_at = $current_date_time;

        if($exStatusActivity->save() && $expeditionActivity->save()){
          if(isset($img)){
            //upload image
            $fileExt = $img->extension();
            $fileName = "IMG-EXPEDITION-".$exStatusActivity->id.$exStatusActivity->ex_id.".".$fileExt;
            $path = public_path().'/uploads/expedition/' ;
            $oldFile = $path.$exStatusActivity->id.$exStatusActivity->ex_id;
   
            $exStatusActivity->img = $fileName;
            $img->move($path, $fileName);
            $exStatusActivity->save();
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

        }else{
          foreach($data as $key => $row) {
            $expeditionActivity->{$key} = $row;
          }

          if($expeditionActivity->save()){
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
      $expeditionActivity = ExpeditionActivity::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;
      $expeditionActivity->deleted_at = $current_date_time;
      $expeditionActivity->deleted_by = $user_id;
      $expeditionActivity->is_deleted = true;


      if($expeditionActivity->save()){
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

  public function getOjk(Request $request) {
    if($request->isMethod('GET')) {
        $data = $request->all();
        $getOjk = Ojk::join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                ->select('ex_master_ojk.*', 'ex_wil_kecamatan.kecamatan', 'ex_master_cabang.cabang_name', 'ex_wil_kabupaten.kabupaten')
                ->where('ex_wil_kecamatan.kecamatan', 'iLike', '%'.$data['kecamatan'].'%')
                ->where('ex_master_ojk.is_deleted', 'f')
                ->get();

        return response()->json([
            'code' => 200,
            'code_message' => 'Success',
            'code_type' => 'Success',
            'data'=> $getOjk
        ], 200);
    
    } else {
        return response()->json([
            'code' => 405,
            'code_message' => 'Method salah',
            'code_type' => 'BadRequest',
        ], 405);
    }
  }

  public function getKenek(Request $request) {
    if($request->isMethod('GET')) {
        $data = $request->all();
        $getDriver = Driver::find($data['id']);
        $getKenek = Kenek::find($getDriver->kenek_id);

        return response()->json([
            'code' => 200,
            'code_message' => 'Success',
            'code_type' => 'Success',
            'data'=> $getKenek
        ], 200);
    
    } else {
        return response()->json([
            'code' => 405,
            'code_message' => 'Method salah',
            'code_type' => 'BadRequest',
        ], 405);
    }
  }

  public function getExpeditionHistoryByDriverId(Request $request){
    if($request->isMethod('GET')) {
      $data = $request->all();
      $expeditionActivityList = ExStatusActivity::leftjoin('all_global_param', 'ex_status_activity.status_approval', 'all_global_param.param_code')
                   ->where('ex_id', $data['ex_id'])
                   ->select('ex_status_activity.*', 'all_global_param.param_name as approval_name')
                   ->orderBy('approval_at', 'DESC')
                   ->paginate();
      
      foreach($expeditionActivityList as $row) {
        $row->data_json = $row->toJson();
      }

      if(!isset($expeditionActivityList)){
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
          'result'=> $expeditionActivityList
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
