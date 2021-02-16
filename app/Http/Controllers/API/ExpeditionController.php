<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\ExpeditionActivity;
use App\Models\ExStatusActivity;
use App\Models\Ojk;
use App\Models\Kenek;
use App\Models\CoaActivity;
use App\Models\Driver;
use App\Models\UserDetail;
use App\Models\Notification;
use App\Models\GlobalParam;
use App\Models\Group;
use Auth;
use DB;
use Carbon\Carbon;
use App\Services\FirebaseService;
// use App\Services\FirebaseServic\Messaging;

class ExpeditionController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'kabupaten, kecamatan, cabang_name, all_global_param.param_name, nomor_inv, otv_payment_method';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
      $whereNotifId = (isset($data['filter_by_id'])) ? $data['filter_by_id'] : '';
      $expeditionActivityList = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
                   ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
                   ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                   ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                   ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                   ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                   ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                   ->leftJoin('ex_master_kenek','expedition_activity.kenek_id', 'ex_master_kenek.id')
                   ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
                   ->where('expedition_activity.is_deleted','false')
                   ->where(function($query) use($whereField, $whereValue) {
                     if($whereValue) {
                       foreach(explode(', ', $whereField) as $idx => $field) {
                         $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                       }
                     }
                   })
                   ->where(function($query) use($whereFilter) {
                     if($whereFilter) {
                         $query->where('otv_payment_method', $whereFilter);
                     }
                   })
                   ->where(function($query) use($whereNotifId) {
                     if($whereNotifId) {
                         $query->where('expedition_activity.id', $whereNotifId);
                     }
                   })
                   ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 
                            'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat', 
                            'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name', 
                            'ex_master_ojk.harga_ojk', 'ex_master_ojk.harga_otv', 'ex_master_kenek.kenek_name')
                   ->orderBy('id', 'ASC')
                   ->paginate();
      
      foreach($expeditionActivityList as $row) {
        $row->jenis_surat_jalan = substr($row->nomor_surat_jalan, 0, 2);
        $exStatusActivity = ExStatusActivity::leftJoin('all_global_param as status_activity', 'ex_status_activity.status_activity', 'status_activity.param_code')
                            ->leftJoin('all_global_param', 'ex_status_activity.status_approval', 'all_global_param.param_code')
                            ->where('ex_status_activity.ex_id', $row->id)
                            ->orderBy('ex_status_activity.id', 'DESC')
                            ->select('all_global_param.param_code as approval_code', 'all_global_param.param_name as approval_name', 'ex_status_activity.*')->first();

        $row->long_lat = $exStatusActivity['long_lat'];
        $row->approval_code = $exStatusActivity['approval_code'];
        $row->approval_name = $exStatusActivity['approval_name'];
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
      $whereField = 'expedition_activity.kabupaten, expedition_activity.kecamatan, expedition_activity.cabang_name, 
      all_global_param.param_name, expedition_activity.nomor_inv';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $platform = (isset($data['from'])) ? $data['from'] : '';
      $whereNotifId = (isset($data['filter_by_id'])) ? $data['filter_by_id'] : '';
      $expeditionActivityList = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
                                ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
                                ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                                ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                                ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                                ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                                ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                                ->leftJoin('ex_master_kenek', 'ex_master_kenek.id', 'expedition_activity.kenek_id')
                                ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
                                ->where('expedition_activity.is_deleted','false')
                                ->where(function($query) use($whereField, $whereValue) {
                                    if($whereValue) {
                                    foreach(explode(', ', $whereField) as $idx => $field) {
                                        $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                                    }
                                    }
                                })
                                ->where(function($query) use($platform) {
                                    if($platform == 'mobile') {
                                        $query->Where('expedition_activity.status_activity','SUBMIT');
                
                                    }else{
                                        $query->whereIn('expedition_activity.status_activity', ['SUBMIT', 'APPROVAL_OJK_DRIVER', 'DRIVER_MENUJU_TUJUAN', 'DRIVER_SAMPAI_TUJUAN']);
                                    }
                                  })
                                  ->where(function($query) use($whereNotifId) {
                                    if($whereNotifId) {
                                        $query->where('expedition_activity.id', $whereNotifId);
                                    }
                                  })
                                ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat', 
                                        'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name',
                                            'ex_master_ojk.harga_ojk', 'ex_master_ojk.harga_otv', 'ex_master_kenek.kenek_name')
                                ->orderBy('id', 'ASC')
                                ->paginate();
                   
      foreach($expeditionActivityList as $row) {
        $row->jenis_surat_jalan = substr($row->nomor_surat_jalan, 0, 2);
        $row->data_json = $row->toJson();


        $approvalCode = ExStatusActivity::leftJoin('all_global_param as status_activity', 'ex_status_activity.status_activity', 'status_activity.param_code')
                        ->leftJoin('all_global_param', 'ex_status_activity.status_approval', 'all_global_param.param_code')
                        ->where('ex_status_activity.ex_id', $row->id)
                        ->orderBy('ex_status_activity.id', 'DESC')
                        ->select('all_global_param.param_code as approval_code', 'all_global_param.param_name as approval_name', 'ex_status_activity.*')->first();

        $allglobalParam = GlobalParam::where('param_code', $row->otv_payment_method)->first();

        if(isset($allglobalParam)){
        $row->otv_payment_method_name = $allglobalParam['param_name'];
        }else{
        $row->otv_payment_method_name = null;
        }

        $row->approval_code = $approvalCode['approval_code'];
        $row->approval_name = $approvalCode['approval_name'];
        $row->otv_no_rek = $approvalCode['no_rek'];
        $row->otv_nominal = $approvalCode['nominal'];
        $row->otv_nama_bank = $approvalCode['rek_name'];
        $row->otv_image = url('/uploads/expedition/'.$approvalCode['img']);
        $row->jenis_surat_jalan = substr($row->nomor_surat_jalan, 0, 2);
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
      $whereField = 'kabupaten, kecamatan, cabang_name, all_global_param.param_name, nomor_inv';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $groupAdmin = Group::where('group_name', 'Admin Kantor')->first();
      $groupOwner = Group::where('group_name', 'Owner')->first();
      $groupId = Auth::user()->group_id;

      $whereNotifId = (isset($data['filter_by_id'])) ? $data['filter_by_id'] : '';
      $expeditionActivityList = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
                   ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
                   ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                   ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                   ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                   ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                   ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                   ->leftJoin('ex_master_kenek', 'ex_master_kenek.id', 'expedition_activity.kenek_id')
                   ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
                   ->where('expedition_activity.is_deleted','false')
                   ->whereIn('expedition_activity.status_activity', ['DRIVER_SELESAI_EKSPEDISI', 'APPROVAL_OTV_DRIVER', 'CLOSED_EXPEDITION', 'WAITING_OWNER'])
                   ->where(function($query) use($whereField, $whereValue) {
                     if($whereValue) {
                       foreach(explode(', ', $whereField) as $idx => $field) {
                         $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                       }
                     }
                   })
                   ->where(function($query) use($groupOwner, $groupId) {
                        if($groupId == $groupOwner->id) {
                            $query->where('status_activity', 'DRIVER_SELESAI_EKSPEDISI')
                                  ->where('otv_payment_method', 'NON_TUNAI')
                                  ->orWhere('status_activity', 'WAITING_OWNER');
                        }
                   })
                   ->where(function($query) use($groupAdmin, $groupId) {
                        if($groupId == $groupAdmin->id) {
                            $query->where('status_activity', 'DRIVER_SELESAI_EKSPEDISI')
                                  ->where('otv_payment_method', 'TUNAI');
                        }
                   })  
                   ->where(function($query) use($whereNotifId) {
                    if($whereNotifId) {
                        $query->where('expedition_activity.id', $whereNotifId);
                    }
                  })
                   ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat', 
                            'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name',
                             'ex_master_ojk.harga_ojk', 'ex_master_ojk.harga_otv', 'ex_master_kenek.kenek_name')
                   ->orderBy('id', 'ASC')
                   ->paginate();
      
      foreach($expeditionActivityList as $key => $row) {
            $approvalCode = ExStatusActivity::leftJoin('all_global_param as status_activity', 'ex_status_activity.status_activity', 'status_activity.param_code')
                            ->leftJoin('all_global_param', 'ex_status_activity.status_activity', 'all_global_param.param_code')
                            ->where('ex_status_activity.ex_id', $row->id)
                            ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
                            ->orderBy('ex_status_activity.id', 'DESC')
                            ->select('all_global_param.param_code as approval_code', 'all_global_param.param_name as approval_name', 'ex_status_activity.*')->first();

            $allglobalParam = GlobalParam::where('param_code', $row->otv_payment_method)->first();

            if(isset($allglobalParam)){
            $row->otv_payment_method_name = $allglobalParam['param_name'];

            }else{
            $row->otv_payment_method_name = null;
            }

            $row->approval_code = $approvalCode['approval_code'];
            $row->approval_name = $approvalCode['approval_name'];
            $row->otv_no_rek = $approvalCode['no_rek'];
            $row->otv_nominal = $approvalCode['nominal'];
            $row->otv_nama_bank = $approvalCode['rek_name'];
            $row->otv_image = url('/uploads/expedition/'.$approvalCode['img']);
            $row->otv_nominal_kurang_bayar = $approvalCode['nominal_kurang_bayar'];
            $row->jenis_surat_jalan = substr($row->nomor_surat_jalan, 0, 2);
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
      
      $factory = new FirebaseService();
      $masterOjk = OJK::where('id', $data['ojk_id'])->select('harga_otv', 'harga_ojk')->first();
      $this->validate($request, [
        // 'no_ExpeditionActivity' => 'required|string|max:255|unique:ExpeditionActivity',
        // 'ExpeditionActivity_name' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);
      unset($data['jenis_surat_jalan']);

      $data['tgl_po'] = date('Y-m-d H:i:s', strtotime($data['tgl_po']));
      $data['tgl_inv'] = date('Y-m-d H:i:s', strtotime($data['tgl_inv']));

      foreach($data as $key => $row) {
        $expeditionActivity->{$key} = $row;
        $expeditionActivity->status_activity = 'SUBMIT';
      }
      $expeditionActivity->harga_ojk = $masterOjk['harga_ojk'];
      $expeditionActivity->harga_otv = $masterOjk['harga_otv'];
      $expeditionActivity->created_at = $current_date_time;
      $expeditionActivity->created_by = $idUser;
      $expeditionActivity->user_id = $idUser;

      if($expeditionActivity->save()) { 
        $code = str_repeat("0", 4 - strlen($expeditionActivity->id)).$expeditionActivity->id;
        $codes = $request->jenis_surat_jalan.date('Y').$code;

        $expeditionActivity->nomor_surat_jalan = $codes;
        $expeditionActivity->save();

        $exStatusActivity = new ExStatusActivity();
        $exStatusActivity->ex_id = $expeditionActivity->id;
        $exStatusActivity->status_activity = $expeditionActivity->status_activity;
        $exStatusActivity->status_approval = 'WAITING_OWNER';
        $exStatusActivity->approval_by = $idUser;
        $exStatusActivity->approval_at = $current_date_time;
        $exStatusActivity->save();

        DB::connection(Auth::user()->schema)->commit();
        $userOwner = User::where('group_id', '8')->where('id_fcm_android','<>','')->get();
        
        foreach($userOwner as $key => $row) {
          $notification = new Notification();
          $notification->content_id = $expeditionActivity->id;
          $notification->content_type = 'expedisi';
          $notification->navigate_to_mobile = 'approal_ojk';
          $notification->navigate_to_web = 'list_ekspedisi';
          $notification->content_title = 'Approval OJK';
          $notification->content_body = 'Ekspedisi '.$expeditionActivity->nomor_inv. ' membutuhkan approval OJK';
          $notification->content_img = '';
          $notification->created_at = $current_date_time;
          $notification->id_group = 8;
          $notification->id_user_to = $row->id;
          $notification->description = '';
          $notification->id_user_from = $idUser;
          $notification->save();

          if($row->id_fcm_android != null){
            $notif = array(
              'title' => $notification->content_title,
              'body' => $notification->content_body
            );
            $datas = array(
              'content_id' => $notification->content_id,
              'content_type' => $notification->content_type,
              'navigate_to_mobile' => $notification->navigate_to_mobile ,
              'navigate_to_web' => $notification->navigate_to_web,
              'content_title' => $notification->content_title,
              'content_body' => $notification->content_body,
              'content_img' => $notification->content_img,
              'created_at' => $notification->created_at,
              'id_group' => $notification->id_group,
              'id_user_to' => $notification->id_user_to,
              'description' => $notification->description,
              'id_user_from' => $notification->id_user_from,
              'updated_at' => $notification->updated_at,
              'id' => $notification->id
            );
            $requests = array(
              'tokenFcm' => $row->id_fcm_android,
              'notif' => $notif,
              'data' => $datas
            );
            $factory->sendNotif($requests);
          }
        }

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
      $lastExActivity = ExStatusActivity::where('ex_id', $data['id'])->orderBy('id', 'DESC')->first();
      $idUser = Auth::user()->id;

      $factory = new FirebaseService();
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
        $img_tujuan = $request->file('img_tujuan');
        $exStatusActivity = new ExStatusActivity();

        unset($data['update_lates_status']);
        
        $expeditionActivity->otv_payment_method = $request->otv_payment_method;
        $expeditionActivity->status_activity = $request->status_activity;
        $expeditionActivity->updated_by = $idUser;
        $expeditionActivity->updated_at = $current_date_time;

        if($expeditionActivity->save()){
          $statusActivityId = GlobalParam::where('param_code', $expeditionActivity->status_activity)->select('id')->first();
              
          unset($data['otv_payment_method']);
          // unset($data['status_activity']);

          DB::connection(Auth::user()->schema)->beginTransaction();

          foreach($data as $key => $row) {
            $exStatusActivity->{$key} = $row;
          }

          $exStatusActivity->approval_by = $idUser;
          $exStatusActivity->approval_at = $current_date_time;

          if(isset($data['nominal'])){
            if($expeditionActivity->harga_otv == $request->nominal){
              $exStatusActivity->nominal_kurang_bayar = 0;
              $idCoaSheet1 = array(18, 17, 20, 19);

              foreach($idCoaSheet1 as $key => $row) {
                $coaActivity = new CoaActivity();
                $coaActivity->activity_id = $statusActivityId['id'];
                $coaActivity->activity_name = $expeditionActivity->status_activity;
                $coaActivity->status = 'ACTIVE';
                $coaActivity->nominal = $exStatusActivity->nominal;
                $coaActivity->rek_no = $exStatusActivity->no_rek;
                $coaActivity->coa_id = $row;
                $coaActivity->ex_id = $expeditionActivity->id;
                $coaActivity->created_at = $current_date_time;
                $coaActivity->created_by = $idUser;
                $coaActivity->rek_name = $exStatusActivity->rek_name;
                $coaActivity->save();
              }

            }else if($request->nominal < $expeditionActivity->harga_otv){
              $exStatusActivity->nominal_kurang_bayar = $expeditionActivity->harga_otv - $request->nominal;
              $idCoaSheet2 = array(18, 17, 20, 19);
              $idCoaSheet3 = array(8, 7, 10, 9);
              
              foreach($idCoaSheet2 as $key => $row) {
                  $coaActivity = new CoaActivity();
                  $coaActivity->activity_id = $statusActivityId['id'];
                  $coaActivity->activity_name = $expeditionActivity->status_activity;
                  $coaActivity->status = 'ACTIVE';
                  $coaActivity->nominal = $exStatusActivity->nominal;
                  $coaActivity->rek_no = $exStatusActivity->no_rek;
                  $coaActivity->coa_id = $row;
                  $coaActivity->ex_id = $expeditionActivity->id;
                  $coaActivity->created_at = $current_date_time;
                  $coaActivity->created_by = $idUser;
                  $coaActivity->rek_name = $exStatusActivity->rek_name;
                  $coaActivity->save();
              }
              
              foreach($idCoaSheet3 as $key => $row) {
                  $coaActivity = new CoaActivity();
                  $coaActivity->activity_id = $statusActivityId['id'];
                  $coaActivity->activity_name = $expeditionActivity->status_activity;
                  $coaActivity->status = 'ACTIVE';
                  $coaActivity->nominal = $exStatusActivity->nominal_kurang_bayar;
                  $coaActivity->rek_no = $exStatusActivity->no_rek;
                  $coaActivity->coa_id = $row;
                  $coaActivity->ex_id = $expeditionActivity->id;
                  $coaActivity->created_at = $current_date_time;
                  $coaActivity->created_by = $idUser;
                  $coaActivity->rek_name = $exStatusActivity->rek_name;
                  $coaActivity->save();
              }
            }
          }
          if(isset($img)){

              //upload image
              $fileExt = $img->extension();
              $fileName = "IMG-EXPEDITION-".$exStatusActivity->ex_id.strtotime(date('YmdHis')).".".$fileExt;
              $path = public_path().'/uploads/expedition/' ;
              $oldFile = $path.$exStatusActivity->ex_id.strtotime(date('YmdHis'));
    
              $exStatusActivity->img = $fileName;

              $img->move($path, $fileName);
          }
            
          if(isset($img_tujuan)){

            //upload image
            $fileExt = $img_tujuan->extension();
            $fileName_tujuan = "IMG-TUJUAN-EXPEDITION-".$exStatusActivity->ex_id.strtotime(date('YmdHis')).".".$fileExt;
            $path_tujuan = public_path().'/uploads/expedition/' ;
            $oldFile_tujuan = $path_tujuan.$exStatusActivity->ex_id.strtotime(date('YmdHis'));
     
            $exStatusActivity->img_tujuan = $fileName_tujuan;

            $img_tujuan->move($path_tujuan, $fileName_tujuan);
          }
          $exStatusActivity->img = !isset($img) ?  $lastExActivity->img : $fileName;
          $exStatusActivity->img_tujuan = !isset($img_tujuan) ?  $lastExActivity->img_tujuan : $fileName_tujuan;
        }

        if($exStatusActivity->save()){
            if($expeditionActivity->status_activity == 'APPROVAL_OJK_DRIVER'){
              
              $idCoaSheet = array(30, 27, 29, 26);
              foreach($idCoaSheet as $key => $row) {
                $coaActivity = new CoaActivity();
                $coaActivity->activity_id = $statusActivityId['id'];
                $coaActivity->activity_name = $expeditionActivity->status_activity;
                $coaActivity->status = 'ACTIVE';
                $coaActivity->nominal = $expeditionActivity->harga_ojk;
                $coaActivity->rek_no = $exStatusActivity->no_rek;
                $coaActivity->coa_id = $row;
                $coaActivity->ex_id = $expeditionActivity->id;
                $coaActivity->created_at = $current_date_time;
                $coaActivity->created_by = $idUser;
                $coaActivity->rek_name = $exStatusActivity->rek_name;
                $coaActivity->save();
              }
                
            }else if($expeditionActivity->status_activity == 'DRIVER_SAMPAI_TUJUAN'){
              if($expeditionActivity->harga_otv == $request->nominal){
                $exStatusActivity->img = !isset($img) ?  $lastExActivity->img : $fileName;
                $exStatusActivity->img_tujuan = !isset($img_tujuan) ?  $lastExActivity->img_tujuan : $fileName_tujuan;
                $exStatusActivity->nominal = $data['nominal'] ? $data['nominal'] :  $lastExActivity->nominal;
                $exStatusActivity->rek_name = $data['rek_name'] ? $data['rek_name'] :  $lastExActivity->rek_name;
                $exStatusActivity->no_rek = $data['no_rek'] ? $data['no_rek'] :  $lastExActivity->no_rek;
                $exStatusActivity->long_lat = $data['long_lat'] ? $data['long_lat'] :  $lastExActivity->long_lat;
                $exStatusActivity->services = $data['services'] ? $data['services'] :  $lastExActivity->services;
                $exStatusActivity->service_charge = $data['service_charge'] ? $data['service_charge'] :  $lastExActivity->service_charge;
                $exStatusActivity->status_destination = $data['status_destination'] ? $data['status_destination'] :  $lastExActivity->status_destination;
                $exStatusActivity->new_address = $data['new_address'] ? $data['new_address'] :  $lastExActivity->new_address;
                $exStatusActivity->nominal_kurang_bayar = 0;
                $exStatusActivity->save();
                $idCoaSheet1 = array(18, 17, 20, 19);

                foreach($idCoaSheet1 as $key => $row) {
                  $coaActivity = new CoaActivity();
                  $coaActivity->activity_id = $statusActivityId['id'];
                  $coaActivity->activity_name = $expeditionActivity->status_activity;
                  $coaActivity->status = 'ACTIVE';
                  $coaActivity->nominal = $exStatusActivity->nominal;
                  $coaActivity->rek_no = $exStatusActivity->no_rek;
                  $coaActivity->coa_id = $row;
                  $coaActivity->ex_id = $expeditionActivity->id;
                  $coaActivity->created_at = $current_date_time;
                  $coaActivity->created_by = $idUser;
                  $coaActivity->rek_name = $exStatusActivity->rek_name;
                  $coaActivity->save();
                }

              }else if($request->nominal < $expeditionActivity->harga_otv){
                $exStatusActivity->img = !isset($img) ?  $lastExActivity->img : $fileName;
                $exStatusActivity->img_tujuan = !isset($img_tujuan) ?  $lastExActivity->img_tujuan : $fileName_tujuan;
                $exStatusActivity->nominal = isset($data['nominal']) ? $data['nominal'] :  $lastExActivity->nominal;
                $exStatusActivity->rek_name = isset($data['rek_name']) ? $data['rek_name'] :  $lastExActivity->rek_name;
                $exStatusActivity->no_rek = isset($data['no_rek']) ? $data['no_rek'] :  $lastExActivity->no_rek;
                $exStatusActivity->long_lat = isset($data['long_lat']) ? $data['long_lat'] :  $lastExActivity->long_lat;
                $exStatusActivity->services = $data['services'] ? $data['services'] :  $lastExActivity->services;
                $exStatusActivity->service_charge = $data['service_charge'] ? $data['service_charge'] :  $lastExActivity->service_charge;
                $exStatusActivity->status_destination = $data['status_destination'] ? $data['status_destination'] :  $lastExActivity->status_destination;
                $exStatusActivity->new_address = $data['new_address'] ? $data['new_address'] :  $lastExActivity->new_address;
                $exStatusActivity->nominal_kurang_bayar = $expeditionActivity->harga_otv - $request->nominal;
                $exStatusActivity->save();
                $idCoaSheet2 = array(18, 17, 20, 19);
                $idCoaSheet3 = array(8, 7, 10, 9);
                
                foreach($idCoaSheet2 as $key => $row) {
                    $coaActivity = new CoaActivity();
                    $coaActivity->activity_id = $statusActivityId['id'];
                    $coaActivity->activity_name = $expeditionActivity->status_activity;
                    $coaActivity->status = 'ACTIVE';
                    $coaActivity->nominal = $exStatusActivity->nominal;
                    $coaActivity->rek_no = $exStatusActivity->no_rek;
                    $coaActivity->coa_id = $row;
                    $coaActivity->ex_id = $expeditionActivity->id;
                    $coaActivity->created_at = $current_date_time;
                    $coaActivity->created_by = $idUser;
                    $coaActivity->rek_name = $exStatusActivity->rek_name;
                    $coaActivity->save();
                }
                
                foreach($idCoaSheet3 as $key => $row) {
                    $coaActivity = new CoaActivity();
                    $coaActivity->activity_id = $statusActivityId['id'];
                    $coaActivity->activity_name = $expeditionActivity->status_activity;
                    $coaActivity->status = 'ACTIVE';
                    $coaActivity->nominal = $exStatusActivity->nominal_kurang_bayar;
                    $coaActivity->rek_no = $exStatusActivity->no_rek;
                    $coaActivity->coa_id = $row;
                    $coaActivity->ex_id = $expeditionActivity->id;
                    $coaActivity->created_at = $current_date_time;
                    $coaActivity->created_by = $idUser;
                    $coaActivity->rek_name = $exStatusActivity->rek_name;
                    $coaActivity->save();
                }
              }
            }

            $driverUser = Driver::where('id', $expeditionActivity->driver_id)->first();
            $userApprove = Auth::user();
            if($exStatusActivity->status_approval == 'APPROVED'){
              $notification = new Notification();
              $notification->content_id = $expeditionActivity->id;
              $notification->content_type = 'expedisi';
              $notification->navigate_to_mobile = 'list_expedisi';
              $notification->navigate_to_web = 'list_expedisi';
              $notification->content_title = 'Informasi Approval Ekspedisi';
              $notification->content_body = 'Ekspedisi dengan nomor invoice '.$expeditionActivity->nomor_inv. ' telah di approve oleh '.$userApprove->name;
              $notification->content_img = '';
              $notification->created_at = $current_date_time;
              $notification->id_user_to = $lastExActivity->approval_by;
              $notification->description = '';
              $notification->id_user_from = $userApprove->id;
              $notification->save();

              $userApprovalDetail = User::where('id', $notification->id_user_to)->where('id_fcm_android','<>','')->first();
             
              if(isset($userApprovalDetail)){
                  $notif = array(
                  'title' => $notification->content_title,
                  'body' => $notification->content_body
                  );
                $datas = array(
                  'content_id' => $notification->content_id,
                  'content_type' => $notification->content_type,
                  'navigate_to_mobile' => $notification->navigate_to_mobile ,
                  'navigate_to_web' => $notification->navigate_to_web,
                  'content_title' => $notification->content_title,
                  'content_body' => $notification->content_body,
                  'content_img' => $notification->content_img,
                  'created_at' => $notification->created_at,
                  'id_group' => $notification->id_group,
                  'id_user_to' => $notification->id_user_to,
                  'description' => $notification->description,
                  'id_user_from' => $notification->id_user_from,
                  'updated_at' => $notification->updated_at,
                  'id' => $notification->id
                );
                $requests = array(
                  'tokenFcm' => $userApprovalDetail->id_fcm_android,
                  'notif' => $notif,
                  'data' => $datas
                );
                $factory->sendNotif($requests);
              }
              $notificationDriver = new Notification();
              $notificationDriver->content_id = $expeditionActivity->id;
              $notificationDriver->content_type = 'expedisi';
              $notificationDriver->navigate_to_mobile = 'driver_expedisi';
              $notificationDriver->navigate_to_web = 'driver_expedisi';
              $notificationDriver->content_title = 'Informasi Ekspedisi Baru';
              $notificationDriver->content_body = 'Tugas ekspedisi baru untuk anda, dengan no invoice '.$expeditionActivity->nomor_inv;
              $notificationDriver->content_img = '';
              $notificationDriver->created_at = $current_date_time;
              $notificationDriver->id_user_to = $driverUser->user_id;
              $notificationDriver->description = '';
              $notificationDriver->id_user_from = $userApprove->id;
              $notificationDriver->save();

              $userDriverDetail = User::where('id', $notificationDriver->id_user_to)->where('id_fcm_android','<>','')->first();
             
              if(isset($userDriverDetail)){
                  $notifs = array(
                    'title' => $notificationDriver->content_title,
                    'body' => $notificationDriver->content_body
                  );
                $datass = array(
                  'content_id' => $notificationDriver->content_id,
                  'content_type' => $notificationDriver->content_type,
                  'navigate_to_mobile' => $notificationDriver->navigate_to_mobile ,
                  'navigate_to_web' => $notificationDriver->navigate_to_web,
                  'content_title' => $notificationDriver->content_title,
                  'content_body' => $notificationDriver->content_body,
                  'content_img' => $notificationDriver->content_img,
                  'created_at' => $notificationDriver->created_at,
                  'id_group' => $notificationDriver->id_group,
                  'id_user_to' => $notificationDriver->id_user_to,
                  'description' => $notificationDriver->description,
                  'id_user_from' => $notificationDriver->id_user_from,
                  'updated_at' => $notificationDriver->updated_at,
                  'id' => $notificationDriver->id
                );

                
                $requestss = array(
                  'tokenFcm' => $userDriverDetail->id_fcm_android,
                  'notif' => $notifs,
                  'data' => $datass
                );
                $factory->sendNotif($requests);
              }
            }else if($exStatusActivity->status_approval == 'REJECTED'){
              $notification = new Notification();
              $notification->content_id = $expeditionActivity->id;
              $notification->content_type = 'expedisi';
              $notification->navigate_to_mobile = 'list_expedisi';
              $notification->navigate_to_web = 'list_ekspedisi';
              $notification->content_title = 'Informasi Approval Ekspedisi';
              $notification->content_body = 'Expedisi dengan nomor invoice '.$expeditionActivity->nomor_inv.' telah di reject oleh '.$userApprove->name;
              $notification->content_img = '';
              $notification->created_at = $current_date_time;
              $notification->id_user_to = $expeditionActivity->user_id;
              $notification->description = '';
              $notification->id_user_from = $userApprove->id;
              $notification->save();

              $userRejectedDetail = User::where('id', $notification->id_user_to)->where('id_fcm_android','<>','')->first();
              
              if(isset($userRejectedDetail)){
                $notif = array(
                  'title' => $notification->content_title,
                  'body' => $notification->content_body
                );
                $datas = array(
                  'content_id' => $notification->content_id,
                  'content_type' => $notification->content_type,
                  'navigate_to_mobile' => $notification->navigate_to_mobile ,
                  'navigate_to_web' => $notification->navigate_to_web,
                  'content_title' => $notification->content_title,
                  'content_body' => $notification->content_body,
                  'content_img' => $notification->content_img,
                  'created_at' => $notification->created_at,
                  'id_group' => $notification->id_group,
                  'id_user_to' => $notification->id_user_to,
                  'description' => $notification->description,
                  'id_user_from' => $notification->id_user_from,
                  'updated_at' => $notification->updated_at,
                  'id' => $notification->id
                );;
                $requests = array(
                  'tokenFcm' => $userRejectedDetail->id_fcm_android,
                  'notif' => $notif,
                  'data' => $datas
                );
                $factory->sendNotif($requests);
              }
            }else if($exStatusActivity->status_approval == 'REVISION'){
              $notification = new Notification();
              $notification->content_id = $expeditionActivity->id;
              $notification->content_type = 'expedisi';
              $notification->navigate_to_mobile = 'list_expedisi';
              $notification->navigate_to_web = 'list_ekspedisi';
              $notification->content_title = 'Informasi Approval Ekspedisi';
              $notification->content_body = 'Expedisi dengan nomor invoice '.$expeditionActivity->nomor_inv.' telah di revisi oleh '.$userApprove->name;
              $notification->content_img = '';
              $notification->created_at = $current_date_time;
              $notification->id_user_to = $expeditionActivity->user_id;
              $notification->description = '';
              $notification->id_user_from = $userApprove->id;
              $notification->save();

              $userReivisionDetail = User::where('id', $notification->id_user_to)->where('id_fcm_android','<>','')->first();
              $notif = array(
                'title' => $notification->content_title,
                'body' => $notification->content_body
              );
              if(isset($userReivisionDetail)){
                $datas = array(
                  'content_id' => $notification->content_id,
                  'content_type' => $notification->content_type,
                  'navigate_to_mobile' => $notification->navigate_to_mobile ,
                  'navigate_to_web' => $notification->navigate_to_web,
                  'content_title' => $notification->content_title,
                  'content_body' => $notification->content_body,
                  'content_img' => $notification->content_img,
                  'created_at' => $notification->created_at,
                  'id_group' => $notification->id_group,
                  'id_user_to' => $notification->id_user_to,
                  'description' => $notification->description,
                  'id_user_from' => $notification->id_user_from,
                  'updated_at' => $notification->updated_at,
                  'id' => $notification->id
                );;
                $requests = array(
                  'tokenFcm' => $userReivisionDetail->id_fcm_android,
                  'notif' => $notif,
                  'data' => $datas
                );
                $factory->sendNotif($requests);
              }
            }else if($exStatusActivity->status_activity == 'DRIVER_SELESAI_EKSPEDISI'){
              if($expeditionActivity->otv_payment_method == 'TUNAI'){
                $userOwner = User::where('group_id', '10')->where('id_fcm_android','<>','')->get();
                foreach($userOwner as $key => $row) {
                  $notification = new Notification();
                  $notification->content_id = $expeditionActivity->id;
                  $notification->content_type = 'expedisi';
                  $notification->navigate_to_mobile = 'approval_otv';
                  $notification->navigate_to_web = 'approval_otv';
                  $notification->content_title = 'Informasi Ekspedisi';
                  $notification->content_body = 'Expedisi dengan nomor invoice '.$expeditionActivity->nomor_inv.' telah selesai';
                  $notification->content_img = '';
                  $notification->created_at = $current_date_time;
                  $notification->description = '';
                  $notification->id_user_to = $row->id;
                  $notification->id_user_from = $userApprove->id;
                  $notification->save();
                  if($row->id_fcm_android != null){
                    $notif = array(
                      'title' => $notification->content_title,
                      'body' => $notification->content_body
                    );
                    $datas = array(
                      'content_id' => $notification->content_id,
                      'content_type' => $notification->content_type,
                      'navigate_to_mobile' => $notification->navigate_to_mobile ,
                      'navigate_to_web' => $notification->navigate_to_web,
                      'content_title' => $notification->content_title,
                      'content_body' => $notification->content_body,
                      'content_img' => $notification->content_img,
                      'created_at' => $notification->created_at,
                      'id_group' => $notification->id_group,
                      'id_user_to' => $notification->id_user_to,
                      'description' => $notification->description,
                      'id_user_from' => $notification->id_user_from,
                      'updated_at' => $notification->updated_at,
                      'id' => $notification->id
                    );
                    $requests = array(
                      'tokenFcm' => $row->id_fcm_android,
                      'notif' => $notif,
                      'data' => $datas
                    );
                    $factory->sendNotif($requests);
                  }
                }
              }else if($expeditionActivity->otv_payment_method == 'NON_TUNAI'){
                $userOwner = User::where('group_id', '8')->where('id_fcm_android','<>','')->get();
                foreach($userOwner as $key => $row) {
                  $notification = new Notification();
                  $notification->content_id = $expeditionActivity->id;
                  $notification->content_type = 'expedisi';
                  $notification->navigate_to_mobile = 'approval_otv';
                  $notification->navigate_to_web = 'approval_otv';
                  $notification->content_title = 'Informasi Ekspedisi';
                  $notification->content_body = 'Ekspedisi dengan nomor invoice '.$expeditionActivity->nomor_inv.' telah selesai';
                  $notification->content_img = '';
                  $notification->created_at = $current_date_time;
                  $notification->description = '';
                  $notification->id_user_to = $row->id;
                  $notification->id_user_from = $userApprove->id;
                  $notification->save();
                  if($row->id_fcm_android != null){
                    $notif = array(
                      'title' => $notification->content_title,
                      'body' => $notification->content_body
                    );
                    $datas = array(
                      'content_id' => $notification->content_id,
                      'content_type' => $notification->content_type,
                      'navigate_to_mobile' => $notification->navigate_to_mobile ,
                      'navigate_to_web' => $notification->navigate_to_web,
                      'content_title' => $notification->content_title,
                      'content_body' => $notification->content_body,
                      'content_img' => $notification->content_img,
                      'created_at' => $notification->created_at,
                      'id_group' => $notification->id_group,
                      'id_user_to' => $notification->id_user_to,
                      'description' => $notification->description,
                      'id_user_from' => $notification->id_user_from,
                      'updated_at' => $notification->updated_at,
                      'id' => $notification->id
                    );
                    $requests = array(
                      'tokenFcm' => $row->id_fcm_android,
                      'notif' => $notif,
                      'data' => $datas
                    );
                    $factory->sendNotif($requests);
                  }
                }
              }
            }else if($exStatusActivity->status_activity == 'WAITING_OWNER'){
              $userOwner = User::where('group_id', '8')->where('id_fcm_android','<>','')->get();
              foreach($userOwner as $key => $row) {
                $notification = new Notification();
                $notification->content_id = $expeditionActivity->id;
                $notification->content_type = 'expedisi';
                $notification->navigate_to_mobile = 'approval_otv';
                $notification->navigate_to_web = 'approval_otv';
                $notification->content_title = 'Informasi Ekspedisi';
                $notification->content_body = 'Ekspedisi dengan nomor invoice '.$expeditionActivity->nomor_inv.' menunggu approval otv';
                $notification->content_img = '';
                $notification->created_at = $current_date_time;
                $notification->description = '';
                $notification->id_user_to = $row->id;
                $notification->id_user_from = $userApprove->id;
                $notification->save();
                if($row->id_fcm_android != null){
                  $notif = array(
                    'title' => $notification->content_title,
                    'body' => $notification->content_body
                  );
                  $datas = array(
                    'content_id' => $notification->content_id,
                    'content_type' => $notification->content_type,
                    'navigate_to_mobile' => $notification->navigate_to_mobile ,
                    'navigate_to_web' => $notification->navigate_to_web,
                    'content_title' => $notification->content_title,
                    'content_body' => $notification->content_body,
                    'content_img' => $notification->content_img,
                    'created_at' => $notification->created_at,
                    'id_group' => $notification->id_group,
                    'id_user_to' => $notification->id_user_to,
                    'description' => $notification->description,
                    'id_user_from' => $notification->id_user_from,
                    'updated_at' => $notification->updated_at,
                    'id' => $notification->id
                  );
                  $requests = array(
                    'tokenFcm' => $row->id_fcm_android,
                    'notif' => $notif,
                    'data' => $datas
                  );
                  $factory->sendNotif($requests);
                }
              }
            }else if($exStatusActivity->status_activity == 'CLOSED_EXPEDITION'){
              $notification = new Notification();
              $notification->content_id = $expeditionActivity->id;
              $notification->content_type = 'expedisi';
              $notification->navigate_to_mobile = 'list_expedisi';
              $notification->navigate_to_web = 'list_expedisi';
              $notification->content_title = 'Informasi Ekspedisi';
              $notification->content_body = 'Ekspedisi dengan nomor invoice '.$expeditionActivity->nomor_inv.' telah selesai';
              $notification->content_img = '';
              $notification->created_at = $current_date_time;
              $notification->id_user_to = $expeditionActivity->user_id;
              $notification->description = '';
              $notification->id_user_from = $userApprove->id;
              $notification->save();

              $userCloseDetail = User::where('id', $notification->id_user_to)->where('id_fcm_android','<>','')->first();
              if(isset($userCloseDetail)){
                $notif = array(
                  'title' => $notification->content_title,
                  'body' => $notification->content_body
                );
                $datas = array(
                  'content_id' => $notification->content_id,
                  'content_type' => $notification->content_type,
                  'navigate_to_mobile' => $notification->navigate_to_mobile ,
                  'navigate_to_web' => $notification->navigate_to_web,
                  'content_title' => $notification->content_title,
                  'content_body' => $notification->content_body,
                  'content_img' => $notification->content_img,
                  'created_at' => $notification->created_at,
                  'id_group' => $notification->id_group,
                  'id_user_to' => $notification->id_user_to,
                  'description' => $notification->description,
                  'id_user_from' => $notification->id_user_from,
                  'updated_at' => $notification->updated_at,
                  'id' => $notification->id
                );;
                $requests = array(
                  'tokenFcm' => $userCloseDetail->id_fcm_android,
                  'notif' => $notif,
                  'data' => $datas
                );
                $factory->sendNotif($requests);
              }
            }
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
            foreach($data as $key => $val) {
                $expeditionActivity->{$key} = $val;
            }
        }

        if($expeditionActivity->save()) {
            DB::connection(Auth::user()->schema)->commit();
            return response()->json([
                'code' => 200,
                'code_message' => 'Berhasil menyimpan data',
                'code_type' => 'Success',
              ], 200);

        } else {
            DB::connection(Auth::user()->schema)->rollback();
            return response()->json([
                'code' => 405,
                'code_message' => 'Method salah',
                'code_type' => 'BadRequest',
              ], 405);
        }

      } else {
        DB::connection(Auth::user()->schema)->rollback();
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

  public function getExpeditionHistoryByNoInvOrNoSuratJalan(Request $request){
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'nomor_inv, nomor_surat_jalan';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $expeditionActivityList = ExStatusActivity::join('expedition_activity', 'expedition_activity.id', 'ex_status_activity.ex_id')
                    ->leftjoin('all_global_param', 'ex_status_activity.status_approval', 'all_global_param.param_code')
                    ->leftjoin('usr_detail', 'ex_status_activity.approval_by', 'usr_detail.id_user')
                    // ->where('all_global_param.param_type', 'EX_STATUS_APPROVAL')
                    ->where(function($query) use($whereField, $whereValue) {
                        foreach(explode(', ', $whereField) as $idx => $field) {
                          $query->orWhere($field, '=', $whereValue);
                        }
                    })
                   ->select('ex_status_activity.*', 'all_global_param.param_name as approval_name',  
                    DB::raw('CONCAT(usr_detail.first_name, \' \', usr_detail.last_name) AS approved_by'))
                   ->orderBy('approval_at', 'DESC')
                   ->paginate();
      
      foreach($expeditionActivityList as $row) {
      
        $row->img = ($row->img) ? url('uploads/expedition/'.$row->img) :url('uploads/sparepart/nia3.png');
        $row->img_tujuan = ($row->img_tujuan ) ? url('uploads/expedition/'.$row->img_tujuan) :url('uploads/sparepart/nia3.png');
 
        $row->data_json = $row->toJson();
      }
   
      return response()->json([
        'code' => 200,
        'code_message' => 'Success',
        'code_type' => 'Success',
        'result'=> $expeditionActivityList
      ], 200);
    
      } else {
        return response()->json([
          'code' => 405,
          'code_message' => 'Method salah',
          'code_type' => 'BadRequest',
          'result'=> null
        ], 405);
      }
  }

  public function getExpeditionHistoryByDriver(Request $request){
    if($request->isMethod('GET')) {
      $data = $request->all();
      $groupDriver = Group::where('group_name', 'Driver')->first();
      $user = Auth::user();
      $whereNotifId = (isset($data['filter_by_id'])) ? $data['filter_by_id'] : '';
      $expeditionActivityList = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
                   ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
                   ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                   ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                   ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                   ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                   ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                   ->leftJoin('ex_master_kenek','expedition_activity.kenek_id', 'ex_master_kenek.id')
                   ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
                   ->where('ex_master_driver.user_id', $user->id)
                   ->where('expedition_activity.is_deleted', 'false')
                   ->where(function($query) use($groupDriver, $user) {
                      if($user->group_id == $groupDriver->id) {
                         $query->whereIn('expedition_activity.status_activity', ['APPROVAL_OJK_DRIVER', 
                                         'DRIVER_MENUJU_TUJUAN', 'DRIVER_SAMPAI_TUJUAN']);
                      }else{
                          $query->whereIn('expedition_activity.status_activity', ['SUBMIT', 'APPROVAL_OJK_DRIVER', 
                                          'DRIVER_MENUJU_TUJUAN', 'DRIVER_SAMPAI_TUJUAN']);
                      }
                    }) 
                    ->where(function($query) use($whereNotifId) {
                      if($whereNotifId) {
                          $query->where('expedition_activity.id', $whereNotifId);
                      }
                    })
                   ->whereIn('expedition_activity.status_activity', ['SUBMIT', 'APPROVAL_OJK_DRIVER', 
                            'DRIVER_MENUJU_TUJUAN', 'DRIVER_SAMPAI_TUJUAN'])
                   ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 
                            'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat', 
                            'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 
                            'ex_master_cabang.cabang_name', 'ex_master_ojk.harga_ojk', 'ex_master_ojk.harga_otv', 
                            'ex_master_kenek.kenek_name')
                   ->orderBy('id', 'ASC')
                   ->paginate();
      
      foreach($expeditionActivityList as $row) {
        $row->jenis_surat_jalan = substr($row->nomor_surat_jalan, 0, 2);
        $exStatusActivity = ExStatusActivity::where('ex_status_activity.ex_id',$row->id)
        ->orderBy('ex_status_activity.updated_at', 'DESC')
        ->select('ex_status_activity.long_lat','ex_status_activity.img', 'ex_status_activity.img_tujuan')->first();
        $row->long_lat = $exStatusActivity['long_lat'];
        $row->img = ($exStatusActivity['img']) ? url('uploads/expedition/'.$exStatusActivity['img']) :'';
        $row->img_tujuan = ($exStatusActivity['img_tujuan']) ? url('uploads/expedition/'.$exStatusActivity['img_tujuan']) :'';
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

  public function getExpeditionHistoryByDriverSelesai(Request $request){
    if($request->isMethod('GET')) {
      $data = $request->all();
      $user = Auth::user();
      $expeditionActivityList = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
                    ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
                    ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                    ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                    ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                    ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                    ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                    ->leftJoin('ex_master_kenek','expedition_activity.kenek_id', 'ex_master_kenek.id')
                    ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
                    ->where('ex_master_driver.user_id', $user->id)
                    ->where('expedition_activity.is_deleted', 'false')
                    ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 
                            'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat', 
                            'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 
                            'ex_master_cabang.cabang_name', 'ex_master_ojk.harga_ojk', 'ex_master_ojk.harga_otv', 
                            'ex_master_kenek.kenek_name')
                    ->orderBy('id', 'ASC')
                    ->paginate();
      
      foreach($expeditionActivityList as $row) {
        $row->jenis_surat_jalan = substr($row->nomor_surat_jalan, 0, 2);
        $exStatusActivity = ExStatusActivity::where('ex_status_activity.ex_id',$row->id)
        ->orderBy('ex_status_activity.updated_at', 'DESC')
        ->select('ex_status_activity.long_lat')->first();
        $row->long_lat = $exStatusActivity['long_lat'];
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

  
  public function getDetailExpeditionByInvoiceAndNoSuratJalan(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'nomor_inv, nomor_surat_jalan';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $expeditionActivityList = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
                   ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
                   ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                   ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                   ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                   ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                   ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                   ->leftJoin('ex_master_kenek','expedition_activity.kenek_id', 'ex_master_kenek.id')
                   ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
                   ->where('expedition_activity.is_deleted','false')
                   ->where(function($query) use($whereField, $whereValue) {
                     if($whereValue) {
                       foreach(explode(', ', $whereField) as $idx => $field) {
                         $query->orWhere($field, '=', $whereValue);
                       }
                     }
                   })
                   ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 
                            'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat', 
                            'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name', 
                            'ex_master_ojk.harga_ojk', 'ex_master_ojk.harga_otv', 'ex_master_kenek.kenek_name')
                   ->orderBy('id', 'DESC')->first();
      
    

      if(!isset($expeditionActivityList)){
        return response()->json([
          'code' => 404,
          'code_message' => 'Data tidak ditemukan',
          'code_type' => 'BadRequest',
          'data'=> null
        ], 404);
      }else{
        $expeditionActivityList->jenis_surat_jalan = substr($expeditionActivityList->nomor_surat_jalan, 0, 2);   
        $exStatusActivity = ExStatusActivity::where('ex_status_activity.ex_id',$expeditionActivityList->id)
        ->leftJoin('all_global_param', 'ex_status_activity.status_activity', 'all_global_param.param_code')
        ->orderBy('ex_status_activity.id', 'DESC')
        ->select('all_global_param.param_code as approval_code', 'all_global_param.param_name as approval_name', 'ex_status_activity.long_lat')->first();
        $expeditionActivityList->long_lat = $exStatusActivity['long_lat'];
        $expeditionActivityList->approval_code = $exStatusActivity['approval_code'];
        $expeditionActivityList->approval_name = $exStatusActivity['approval_name'];
        $expeditionActivityList->data_json = $expeditionActivityList->toJson();
      
        return response()->json([
          'code' => 200,
          'code_message' => 'Success',
          'code_type' => 'Success',
          'data'=> $expeditionActivityList
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
  
  public function getExpeditionHistoryByIdDriverAndPeriode(Request $request){
    if($request->isMethod('GET')) {
      $data = $request->all();
      $expeditionActivityList = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
                    ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
                    ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                    ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                    ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                    ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                    ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                    ->leftJoin('ex_master_kenek','expedition_activity.kenek_id', 'ex_master_kenek.id')
                    ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
                    ->where('ex_master_driver.id', $data['idDriver'])
                    ->whereYear('expedition_activity.updated_at', $data['year'])
                    ->whereMonth('expedition_activity.updated_at', $data['month'])
                    ->where('expedition_activity.status_activity', 'CLOSED_EXPEDITION')
                    ->where('expedition_activity.is_deleted', 'false')
                    ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 
                            'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat', 
                            'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 
                            'ex_master_cabang.cabang_name', 'ex_master_ojk.harga_ojk', 'ex_master_ojk.harga_otv', 
                            'ex_master_kenek.kenek_name')
                    ->orderBy('id', 'ASC')
                    ->paginate();
      
      foreach($expeditionActivityList as $row) {
        $row->jenis_surat_jalan = substr($row->nomor_surat_jalan, 0, 2);
        $exStatusActivity = ExStatusActivity::where('ex_status_activity.ex_id',$row->id)
        ->orderBy('ex_status_activity.updated_at', 'DESC')
        ->select('ex_status_activity.long_lat')->first();
        $row->long_lat = $exStatusActivity['long_lat'];
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

  public function testNotif(Request $request){
      $factory = new FirebaseService();
      $user = Auth::user();
      $notif = array(
        'title' => 'hello',
        'body' => 'test test test'
      );
      $data = array(
        'title' => 'hello',
        'description' => 'test test test'
      );
      $requests = array(
        'tokenFcm' => $user->id_fcm_android,
        'notif' => $notif,
        'data' => $data
      );
      $factory->sendNotif($requests);
  }

}
