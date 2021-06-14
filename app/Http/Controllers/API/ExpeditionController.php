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
use App\Models\Truck;
use App\Models\UserDetail;
use App\Models\Notification;
use App\Models\GlobalParam;
use App\Models\Group;
use App\Models\Ban;
use Auth;
use DB;
use Carbon\Carbon;
use App\Services\FirebaseService;
use Validator;
use App\Services\FirebaseServic\Messaging;

class ExpeditionController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $cekRole = $this->checkRoles();
      $ids = null;

      if($cekRole) {
        $ids = json_decode($cekRole, true);
      }

      $data = $request->all();
      $whereField = 'kabupaten, ex_wil_kecamatan.kecamatan, cabang_name, all_global_param.param_name, nomor_inv, otv_payment_method, ex_master_driver.driver_name, expedition_activity.nomor_surat_jalan';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $whereFilter = (isset($data['where_filter'])) ? $data['where_filter'] : '';
      $whereNotifId = (isset($data['filter_by_id'])) ? $data['filter_by_id'] : '';
      $statusCode = isset($data['status_code']) ? $data['status_code'] : false;
      // echo $whereValue; die();
      $expeditionActivityList = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
                   ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
                   ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                   ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                   ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                   ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                   ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                   ->join('public.users', 'users.id', 'expedition_activity.user_id')
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
                   ->where(function($query) use($ids) {
                     if($ids) {
                        $query->whereIn('users.cabang_id', $ids);
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
                   ->where(function($query) use($statusCode) {
                     if($statusCode) {
                         $query->where('expedition_activity.status_activity', $statusCode);
                     }
                   })
                   ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'all_global_param.param_code as status_code',
                            'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat',
                            'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name',
                            'expedition_activity.harga_ojk', 'expedition_activity.harga_otv', 'ex_master_kenek.kenek_name')
                   ->orderBy('id', 'DESC')
                   ->paginate();

      foreach($expeditionActivityList as $row) {
        $row->jenis_surat_jalan = substr($row->nomor_surat_jalan, 0, 2);
        $exStatusActivity = ExStatusActivity::leftJoin('all_global_param as status_activity', 'ex_status_activity.status_activity', 'status_activity.param_code')
                            ->leftJoin('all_global_param', 'ex_status_activity.status_approval', 'all_global_param.param_code')
                            ->where('ex_status_activity.ex_id', $row->id)
                            ->orderBy('ex_status_activity.created_at', 'DESC')
                            ->select('all_global_param.param_code as approval_code', 'all_global_param.param_name as approval_name', 'ex_status_activity.*')->first();

        $row->long_lat = $exStatusActivity['long_lat'];
        $row->toko = $row->toko ? $row->toko : '';
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
      $cekRole = $this->checkRoles();
      $ids = null;

      if($cekRole) {
        $ids = json_decode($cekRole, true);
      }
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
                                ->join('public.users', 'users.id', 'expedition_activity.user_id')
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
                                ->where(function($query) use($ids) {
                                    if($ids) {
                                        $query->whereIn('users.cabang_id', $ids);
                                    }
                                })
                                ->where(function($query) use($platform) {
                                    if($platform == 'mobile') {
                                        $query->Where('expedition_activity.status_activity', 'SUBMIT');
                                        $query->orWhere('expedition_activity.is_approve', 1);

                                    }else{
                                        // $query->whereIn('expedition_activity.status_activity', ['SUBMIT', 'APPROVAL_OJK_DRIVER', 'DRIVER_MENUJU_TUJUAN', 'DRIVER_SAMPAI_TUJUAN']);
                                        $query->Where('expedition_activity.status_activity', 'SUBMIT');
                                        $query->orWhere('expedition_activity.is_approve', 1);
                                    }
                                })
                                ->where(function($query) use($whereNotifId) {
                                    if($whereNotifId) {
                                        $query->where('expedition_activity.id', $whereNotifId);
                                    }
                                })
                                ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat',
                                        'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name',
                                            'expedition_activity.harga_ojk', 'expedition_activity.harga_otv', 'ex_master_kenek.kenek_name')
                                ->orderBy('expedition_activity.created_at', 'ASC')
                                ->paginate();

      foreach($expeditionActivityList as $row) {
        $row->jenis_surat_jalan = substr($row->nomor_surat_jalan, 0, 2);
        $row->driver_action = $row->status_activity !== "SUBMIT" ? true : false;
        $row->data_json = $row->toJson();

        $approvalCode = ExStatusActivity::leftJoin('all_global_param as status_activity', 'ex_status_activity.status_activity', 'status_activity.param_code')
                        ->leftJoin('all_global_param', 'ex_status_activity.status_approval', 'all_global_param.param_code')
                        ->where('ex_status_activity.ex_id', $row->id)
                        ->orderBy('ex_status_activity.created_at', 'DESC')
                        ->select('all_global_param.param_code as approval_code', 'all_global_param.param_name as approval_name', 'ex_status_activity.*')->first();

        $allglobalParam = GlobalParam::where('param_code', $row->otv_payment_method)->first();

        if(isset($allglobalParam)){
            $row->otv_payment_method_name = $allglobalParam['param_name'];

        } else {
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
      $cekRole = $this->checkRoles();
      $ids = null;

      if($cekRole) {
        $ids = json_decode($cekRole, true);
      }
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
                                ->join('public.users', 'users.id', 'expedition_activity.user_id')
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
                                    ->where(function($query) use($ids) {
                                        if($ids) {
                                            $query->whereIn('users.cabang_id', $ids);
                                        }
                                    })
                                ->where(function($query) use($groupOwner, $groupId) {
                                        if($groupId == $groupOwner->id) {
                                            $query->where('status_activity', 'WAITING_OWNER');
                                        }
                                })
                                ->where(function($query) use($groupAdmin, $groupId) {
                                        if($groupId == $groupAdmin->id) {
                                            $query->where('status_activity', 'DRIVER_SELESAI_EKSPEDISI');
                                        }
                                })
                                ->where(function($query) use($whereNotifId) {
                                    if($whereNotifId) {
                                        $query->where('expedition_activity.id', $whereNotifId);
                                    }
                                })
                                ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat',
                                            'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name',
                                            'expedition_activity.harga_ojk', 'expedition_activity.harga_otv', 'ex_master_kenek.kenek_name')
                                ->orderBy('expedition_activity.created_at', 'ASC')
                                ->paginate();

      foreach($expeditionActivityList as $key => $row) {
            $approvalCode = ExStatusActivity::leftJoin('all_global_param as status_activity', 'ex_status_activity.status_activity', 'status_activity.param_code')
                            ->leftJoin('all_global_param', 'ex_status_activity.status_activity', 'all_global_param.param_code','ex_status_activity.nominal_lebih_bayar')
                            ->where('ex_status_activity.ex_id', $row->id)
                            ->orderBy('ex_status_activity.created_at', 'DESC')
                            ->select('all_global_param.param_code as approval_code', 'all_global_param.param_name as approval_name', 'ex_status_activity.*')->first();

            $allglobalParam = GlobalParam::where('param_code', $row->otv_payment_method)->first();

            if(isset($allglobalParam)){
            $row->otv_payment_method_name = $allglobalParam['param_name'];

            }else{
            $row->otv_payment_method_name = null;
            }
            $row->nominal_lebih_bayar = $approvalCode['nominal_lebih_bayar'];
            $row->id_exAct = $approvalCode['id'];
            $row->approval_code = $approvalCode['approval_code'];
            $row->approval_name = $approvalCode['approval_name'];
            $row->otv_no_rek = $approvalCode['no_rek'];
            $row->otv_nominal = $approvalCode['nominal'];
            $row->otv_nama_bank = $approvalCode['rek_name'];
            $row->otv_image = url('/uploads/expedition/'.$approvalCode['img']);
            $row->otv_nominal_kurang_bayar = $approvalCode['nominal_kurang_bayar'];
            $row->jenis_surat_jalan = substr($row->nomor_surat_jalan, 0, 2);
            $row->services = $approvalCode['services'];
            $row->service_charge = $approvalCode['service_charge'];
            $row->status_destination = $approvalCode['status_destination'];
            $row->new_address = $approvalCode['new_address'];
            $row->extra_price = isset($exStatusActivity['extra_price']) ? $exStatusActivity['extra_price'] : null;
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
      if(isset($data['ojk_id'])){
      // $factory = new FirebaseService();
      $masterOjk = OJK::where('id', $data['ojk_id'])->select('harga_otv', 'harga_ojk')->first();
    //   $this->validate($request, [
    //       'nomor_inv' => 'required|string|max:255|unique:'.Auth::user()->schema.'.expedition_activity',
    //       // 'no_ExpeditionActivity' => 'required|string|max:255|unique:ExpeditionActivity',
    //     // 'ExpeditionActivity_name' => 'required|string|max:255',
    //   ]);
      $rules = ['nomor_inv' => 'required|string|max:255|iunique:'.Auth::user()->schema.'.expedition_activity',];
      $validator = Validator::make($data, $rules);

      if($validator->fails()) {
         return response()->json([
            'code' => 405,
            'code_message' => 'Nomor Invoice sudah ada',
            'code_type' => 'BadRequest',
            'result'=> null
         ],405);
      }

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

        if($request->jenis_surat_jalan) {
            $expeditionActivity->nomor_surat_jalan = $codes;
        }
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
          $notification->navigate_to_mobile = 'approval_ojk';
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

          if($row->id_fcm_android != null || $row->id_fcm_android != '') {
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
                //   $factory->sendNotif($requests);
              }

          }
          $driverUser = Driver::where('id', $expeditionActivity->driver_id)->first();
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
          $notificationDriver->id_user_from = $idUser;
          $notificationDriver->save();

          $userDriverDetail = User::where('id', $notificationDriver->id_user_to)->where('id_fcm_android','<>','')->first();

          if(isset($userDriverDetail)){
            if($userDriverDetail->id_fcm_android != null || $userDriverDetail->id_fcm_android != ''){
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
            // $factory->sendNotif($requestss);
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
      }else{
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
      $isUpdate = false;
      $data = $request->all();
      $expeditionActivity = ExpeditionActivity::find($data['id']);
      $lastExActivity = ExStatusActivity::where('ex_id', $data['id'])->orderBy('created_at', 'DESC')->first();
      $allExActivity = ExStatusActivity::where('ex_id', $data['id'])->where('status_activity', 'APPROVAL_OJK_DRIVER')->where('status_approval', 'APPROVED')->get();
      $idUser = Auth::user()->id;

      if(isset($data['truck_id'])) {
          $truckDetail = Truck::find($data['truck_id']);
      }

      $trucktipe = GlobalParam::where('param_code', 'TRUCK')->where('param_type', 'TRUCK_TYPE')->first();
      $idNonKenek = null;

      if(isset($data['kenek_id'])) {
          $idNonKenek = Kenek::where('kenek_name', 'Tidak ada Kenek (TK)')->first()->id;
      }

      // $factory = new FirebaseService();
      $current_date_time = Carbon::now()->toDateTimeString();

      $statusActivityParam = $request->update_lates_status;

      $this->validate($request, [
        // 'no_ExpeditionActivity' => 'required|string|max:255|unique:ExpeditionActivity,no_ExpeditionActivity,'.$data['id'].',id',
        // 'ExpeditionActivity_name' => 'required|string|max:255',
      ]);

      if($request->status_activity == 'APPROVAL_OJK_DRIVER' && $expeditionActivity->is_approve == 1  && ($expeditionActivity->status_activity == 'DRIVER_SAMPAI_TUJUAN' || $expeditionActivity->status_activity == 'DRIVER_MENUJU_TUJUAN')) {
          $current_date_time = date('Y-m-d H:i:s', strtotime($lastExActivity->created_at.' -1 hour'));
          $statusDinamis = $expeditionActivity->status_activity;
          $isUpdate = true;
      }

      if($request->status_activity == 'DRIVER_MENUJU_TUJUAN' && !count($allExActivity)) {
          $expeditionActivity->is_approve = 1;
      }

      if(($request->status_activity == 'DRIVER_MENUJU_TUJUAN' && $data['kenek_id'] == $idNonKenek) && (isset($data['truck_id']) && $truckDetail->truck_type == $trucktipe->id )) {
          $expeditionActivity->harga_ojk = $expeditionActivity->harga_ojk - 60000;
      }

      if($request->status_activity == 'DRIVER_SELESAI_EKSPEDISI') {
          $ban = Ban::where('truck_id', $expeditionActivity->truck_id)->get();

          foreach($ban as $key => $val) {
              $bans = Ban::find($val->id);
              $bans->total_ritasi = $bans->total_ritasi + 1;
              $bans->save();
          }
      }

      if($request->status_activity == 'DRIVER_SELESAI_EKSPEDISI' && !count($allExActivity)) {
          DB::connection(Auth::user()->schema)->rollback();
          return response()->json([
              'code' => 405,
              'code_message' => 'Expedisi belum di Approve oleh Owner, Harap Hubungi Owner',
              'code_type' => 'BadRequest',
          ], 405);
      }

      unset($data['_token']);
      unset($data['id']);
      unset($data['jenis_surat_jalan']);

      if(!$statusActivityParam) {
          $masterOjk = OJK::where('id', $data['ojk_id'])->select('harga_otv', 'harga_ojk')->first();
          $expeditionActivity->harga_ojk = $masterOjk['harga_ojk'];
          $expeditionActivity->harga_otv = $masterOjk['harga_otv'];
      }

      $expeditionActivity->updated_by = $idUser;
      $expeditionActivity->updated_at = $current_date_time;

      if($statusActivityParam){
        $img = $request->file('img');
        $img_tujuan = $request->file('img_tujuan');
        $exStatusActivity = new ExStatusActivity();

        unset($data['update_lates_status']);

        if(isset($request->harga_otv) || isset($request->harga_ojk)) {
            $expeditionActivity->harga_otv = $request->harga_otv;
            $expeditionActivity->harga_ojk = $request->harga_ojk;
        }

        if(isset($request->kenek_id)) {
            $expeditionActivity->kenek_id = $request->kenek_id;
        }

        if(isset($request->penagihan_id)) {
            $expeditionActivity->penagihan_id = $request->penagihan_id;
        }

        DB::connection(Auth::user()->schema)->beginTransaction();

        $expeditionActivity->otv_payment_method = $request->otv_payment_method;
        $expeditionActivity->status_activity = $request->status_activity;
        $expeditionActivity->updated_by = $idUser;
        $expeditionActivity->updated_at = $current_date_time;

        if($expeditionActivity->save()){
          $statusActivityId = GlobalParam::where('param_code', $expeditionActivity->status_activity)->select('id')->first();

          unset($data['otv_payment_method']);
          unset($data['harga_otv']);
          unset($data['harga_ojk']);
          unset($data['kenek_id']);
          unset($data['penagihan_id']);
          // unset($data['status_activity']);

          foreach($data as $key => $row) {
            $exStatusActivity->{$key} = $row;
          }

          $exStatusActivity->approval_by = $idUser;
          $exStatusActivity->approval_at = $current_date_time;

          if(isset($data['nominal'])){
            if($expeditionActivity->harga_otv == $request->nominal || $request->nominal == 0){
              $exStatusActivity->nominal = $expeditionActivity->harga_otv;
              $exStatusActivity->nominal_kurang_bayar = 0;
              $idCoaSheet1 = array(18, 17, 20, 19);

              if($request->status_activity != 'WAITING_OWNER') {
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
              }

            }else if($request->nominal < $expeditionActivity->harga_otv && $request->nominal != 0){
              $exStatusActivity->nominal_kurang_bayar = $expeditionActivity->harga_otv - $request->nominal;
              $idCoaSheet2 = array(18, 17, 20, 19);
              $idCoaSheet3 = array(8, 7, 10, 9);

              if($request->status_activity != 'WAITING_OWNER') {
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

            } elseif($request->nominal > $expeditionActivity->harga_otv && $request->nominal != 0) {
                $exStatusActivity->nominal_lebih_bayar = $request->nominal - $expeditionActivity->harga_otv;
                // $idCoaSheet2 = array(18, 17, 20, 19);
                // $idCoaSheet3 = array(8, 7, 10, 9);

                // if($request->status_activity != 'WAITING_OWNER') {
                //     foreach($idCoaSheet2 as $key => $row) {
                //         $coaActivity = new CoaActivity();
                //         $coaActivity->activity_id = $statusActivityId['id'];
                //         $coaActivity->activity_name = $expeditionActivity->status_activity;
                //         $coaActivity->status = 'ACTIVE';
                //         $coaActivity->nominal = $exStatusActivity->nominal;
                //         $coaActivity->rek_no = $exStatusActivity->no_rek;
                //         $coaActivity->coa_id = $row;
                //         $coaActivity->ex_id = $expeditionActivity->id;
                //         $coaActivity->created_at = $current_date_time;
                //         $coaActivity->created_by = $idUser;
                //         $coaActivity->rek_name = $exStatusActivity->rek_name;
                //         $coaActivity->save();
                //     }

                //     foreach($idCoaSheet3 as $key => $row) {
                //         $coaActivity = new CoaActivity();
                //         $coaActivity->activity_id = $statusActivityId['id'];
                //         $coaActivity->activity_name = $expeditionActivity->status_activity;
                //         $coaActivity->status = 'ACTIVE';
                //         $coaActivity->nominal = $exStatusActivity->nominal_kurang_bayar;
                //         $coaActivity->rek_no = $exStatusActivity->no_rek;
                //         $coaActivity->coa_id = $row;
                //         $coaActivity->ex_id = $expeditionActivity->id;
                //         $coaActivity->created_at = $current_date_time;
                //         $coaActivity->created_by = $idUser;
                //         $coaActivity->rek_name = $exStatusActivity->rek_name;
                //         $coaActivity->save();
                //     }
                // }
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
          $exStatusActivity->services = isset($data['services']) ? $data['services'] :  $lastExActivity->services;
          $exStatusActivity->service_charge = isset($data['service_charge']) ? $data['service_charge'] :  $lastExActivity->service_charge;
          $exStatusActivity->status_destination = isset($data['status_destination']) ? $data['status_destination'] :  $lastExActivity->status_destination;
          $exStatusActivity->new_address = isset($data['new_address']) ? $data['new_address'] :  $lastExActivity->new_address;
          $exStatusActivity->extra_price = isset($data['extra_price']) ? $data['extra_price'] :  $lastExActivity->extra_price;
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
              if($expeditionActivity->harga_otv == $request->nominal || $request->nominal == 0) {
                $data['nominal'] = $expeditionActivity->harga_otv;
                $exStatusActivity->img = !isset($img) ?  $lastExActivity->img : $fileName;
                $exStatusActivity->img_tujuan = !isset($img_tujuan) ?  $lastExActivity->img_tujuan : $fileName_tujuan;
                $exStatusActivity->nominal = isset($data['nominal']) && $data['nominal'] ? $data['nominal'] :  $lastExActivity->nominal;
                $exStatusActivity->rek_name = isset($data['rek_name']) && $data['rek_name'] ? $data['rek_name'] :  $lastExActivity->rek_name;
                $exStatusActivity->no_rek = isset($data['no_rek']) && $data['no_rek'] ? $data['no_rek'] :  $lastExActivity->no_rek;
                $exStatusActivity->long_lat = isset($data['long_lat']) && $data['long_lat'] ? $data['long_lat'] :  $lastExActivity->long_lat;
                $exStatusActivity->services = isset($data['services']) && $data['services'] ? $data['services'] :  $lastExActivity->services;
                $exStatusActivity->service_charge = isset($data['service_charge']) && $data['service_charge'] ? $data['service_charge'] :  $lastExActivity->service_charge;
                $exStatusActivity->status_destination = isset($data['status_destination']) && $data['status_destination'] ? $data['status_destination'] :  $lastExActivity->status_destination;
                $exStatusActivity->new_address = isset($data['new_address']) && $data['new_address'] ? $data['new_address'] :  $lastExActivity->new_address;
                $exStatusActivity->extra_price = isset($data['extra_price']) && $data['extra_price'] ? $data['extra_price'] :  $lastExActivity->extra_price;
                $exStatusActivity->nominal_kurang_bayar = 0;
                $exStatusActivity->save();

              }elseif(($request->nominal < $expeditionActivity->harga_otv) && $request->nominal != 0){
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
                $exStatusActivity->extra_price = isset($data['extra_price']) ? $data['extra_price'] :  $lastExActivity->extra_price;
                $exStatusActivity->nominal_kurang_bayar = $expeditionActivity->harga_otv - $request->nominal;
                $exStatusActivity->save();

              } elseif(($request->nominal > $expeditionActivity->harga_otv) && $request->nominal != 0){
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
                $exStatusActivity->extra_price = isset($data['extra_price']) ? $data['extra_price'] :  $lastExActivity->extra_price;
                $exStatusActivity->nominal_lebih_bayar = $request->nominal - $expeditionActivity->harga_otv;
                $exStatusActivity->save();
              }
            }

            $driverUser = Driver::where('id', $expeditionActivity->driver_id)->first();
            $userApprove = Auth::user();
            if($expeditionActivity->status_activity == 'APPROVAL_OJK_DRIVER'){
              if($exStatusActivity->status_approval == 'APPROVED'){
                $notification = new Notification();
                $notification->content_id = $expeditionActivity->id;
                $notification->content_type = 'expedisi';
                $notification->navigate_to_mobile = 'list_expedisi';
                $notification->navigate_to_web = 'approval-ojk-driver';
                $notification->content_title = 'Informasi Approval Ekspedisi';
                $notification->content_body = 'Ekspedisi dengan nomor invoice '.$expeditionActivity->nomor_inv. ' telah di approve oleh '.$userApprove->name;
                $notification->content_img = '';
                $notification->created_at = $current_date_time;
                $notification->id_user_to = $expeditionActivity->created_by;
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

                  if($userApprovalDetail->id_fcm_android != null || $userApprovalDetail->id_fcm_android != '') {
                      $requests = array(
                        'tokenFcm' => $userApprovalDetail->id_fcm_android,
                        'notif' => $notif,
                        'data' => $datas
                      );
                      // $factory->sendNotif($requests);
                  }
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
                  );

                  if($userRejectedDetail->id_fcm_android != null || $userRejectedDetail->id_fcm_android != '') {
                      $requests = array(
                      'tokenFcm' => $userRejectedDetail->id_fcm_android,
                      'notif' => $notif,
                      'data' => $datas
                      );

                      // $factory->sendNotif($requests);
                  }
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

                if(isset($userReivisionDetail)){
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

                  if($userReivisionDetail->id_fcm_android != null || $userReivisionDetail->id_fcm_android != '') {
                      $requests = array(
                        'tokenFcm' => $userReivisionDetail->id_fcm_android,
                        'notif' => $notif,
                        'data' => $datas
                      );
                      // $factory->sendNotif($requests);
                  }
                }
              }
            }else if($exStatusActivity->status_activity == 'DRIVER_SELESAI_EKSPEDISI'){
              if($expeditionActivity->otv_payment_method == 'TUNAI'){
                $userAdmin = User::where('group_id', '10')->where('id_fcm_android','<>','')->get();
                foreach($userAdmin as $key => $row) {
                  $notification = new Notification();
                  $notification->content_id = $expeditionActivity->id;
                  $notification->content_type = 'expedisi';
                  $notification->navigate_to_mobile = 'approval_otv';
                  $notification->navigate_to_web = 'approval_otv';
                  $notification->content_title = 'Informasi Ekspedisi';
                  $notification->content_body = 'Expedisi dengan nomor invoice '.$expeditionActivity->nomor_inv.' menunggu approval penyelesaian';
                  $notification->content_img = '';
                  $notification->created_at = $current_date_time;
                  $notification->description = '';
                  $notification->id_user_to = $row->id;
                  $notification->id_user_from = $userApprove->id;
                  $notification->save();
                  if($row->id_fcm_android != null || $row->id_fcm_android != ''){
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
                        // $factory->sendNotif($requests);

                  }
                }
              }else if($expeditionActivity->otv_payment_method == 'NON_TUNAI'){
                $userOwner = User::where('group_id', '8')->where('id_fcm_android','<>','')->get();
                foreach($userOwner as $key => $row) {
                  $notifications = new Notification();
                  $notifications->content_id = $expeditionActivity->id;
                  $notifications->content_type = 'expedisi';
                  $notifications->navigate_to_mobile = 'approval_otv';
                  $notifications->navigate_to_web = 'approval_otv';
                  $notifications->content_title = 'Informasi Ekspedisi';
                  $notifications->content_body = 'Ekspedisi dengan nomor invoice '.$expeditionActivity->nomor_inv.' menunggu approval penyelesaian';
                  $notifications->content_img = '';
                  $notifications->created_at = $current_date_time;
                  $notifications->description = '';
                  $notifications->id_user_to = $row->id;
                  $notifications->id_user_from = $userApprove->id;
                  $notifications->save();
                  if($row->id_fcm_android != null || $row->id_fcm_android != ''){
                    $notifs = array(
                      'title' => $notifications->content_title,
                      'body' => $notifications->content_body
                    );
                    $datass = array(
                      'content_id' => $notifications->content_id,
                      'content_type' => $notifications->content_type,
                      'navigate_to_mobile' => $notifications->navigate_to_mobile ,
                      'navigate_to_web' => $notifications->navigate_to_web,
                      'content_title' => $notifications->content_title,
                      'content_body' => $notifications->content_body,
                      'content_img' => $notifications->content_img,
                      'created_at' => $notifications->created_at,
                      'id_group' => $notifications->id_group,
                      'id_user_to' => $notifications->id_user_to,
                      'description' => $notifications->description,
                      'id_user_from' => $notifications->id_user_from,
                      'updated_at' => $notifications->updated_at,
                      'id' => $notifications->id
                    );
                    $requestss = array(
                      'tokenFcm' => $row->id_fcm_android,
                      'notif' => $notifs,
                      'data' => $datass
                    );
                    // $factory->sendNotif($requestss);
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
                $notification->content_body = 'Ekspedisi dengan nomor invoice '.$expeditionActivity->nomor_inv.' menunggu approval penyelesaian';
                $notification->content_img = '';
                $notification->created_at = $current_date_time;
                $notification->description = '';
                $notification->id_user_to = $row->id;
                $notification->id_user_from = $userApprove->id;
                $notification->save();
                if($row->id_fcm_android != null || $row->id_fcm_android != ''){
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
                  // $factory->sendNotif($requests);

                }
              }
            }else if($exStatusActivity->status_activity == 'CLOSED_EXPEDITION'){
              $notification = new Notification();
              $notification->content_id = $expeditionActivity->id;
              $notification->content_type = 'expedisi';
              $notification->navigate_to_mobile = 'list_expedisi';
              $notification->navigate_to_web = 'approval-ojk-driver';
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
                if($userCloseDetail->id_fcm_android != null || $userCloseDetail->id_fcm_android != ''){
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
                        'tokenFcm' => $userCloseDetail->id_fcm_android,
                        'notif' => $notif,
                        'data' => $datas
                      );
                      // $factory->sendNotif($requests);

                }
              }
            }


            if($isUpdate) {
                $expeditionActivity->is_approve = 0;
                $expeditionActivity->status_activity = $statusDinamis;
                $expeditionActivity->save();

                $exStatusActivity->created_at = $current_date_time;
                $exStatusActivity->save();
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
      $cekRole = $this->checkRoles();
      $ids = null;

      if($cekRole) {
        $ids = json_decode($cekRole, true);
      }

      $data = $request->all();
      $whereField = 'nomor_inv, nomor_surat_jalan';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $expeditionActivityList = ExStatusActivity::join('expedition_activity', 'expedition_activity.id', 'ex_status_activity.ex_id')
                    ->leftjoin('all_global_param', 'ex_status_activity.status_approval', 'all_global_param.param_code')
                    ->leftjoin('usr_detail', 'ex_status_activity.approval_by', 'usr_detail.id_user')
                    ->join('public.users', 'users.id', 'expedition_activity.user_id')
                    ->where(function($query) use($whereField, $whereValue) {
                        foreach(explode(', ', $whereField) as $idx => $field) {
                          $query->orWhere($field, '=', $whereValue);
                        }
                    })
                    ->where(function($query) use($ids) {
                        if($ids) {
                           $query->whereIn('users.cabang_id', $ids);
                        }
                    })
                   ->select('ex_status_activity.*', 'all_global_param.param_name as approval_name',
                    DB::raw('CONCAT(usr_detail.first_name, \' \', usr_detail.last_name) AS approved_by'))
                   ->orderBy('ex_status_activity.updated_at', 'DESC')
                   ->paginate();

      foreach($expeditionActivityList as $row) {

        $row->img = ($row->img) ? url('uploads/expedition/'.$row->img) :url('uploads/sparepart/nia3.png');
        $row->created_at = $row->updated_at;
        $row->img_tujuan = ($row->img_tujuan ) ? url('uploads/expedition/'.$row->img_tujuan) :url('uploads/sparepart/nia3.png');
        $row->long_lat = (null == $row->long_lat || '' == $row->long_lat) ? '0.0,0.0' : $row->long_lat;
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
                         $query->whereIn('expedition_activity.status_activity', ['SUBMIT', 'APPROVAL_OJK_DRIVER',
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
                            'ex_master_cabang.cabang_name', 'expedition_activity.harga_ojk', 'expedition_activity.harga_otv',
                            'ex_master_kenek.kenek_name')
                   ->orderBy('id', 'ASC')
                   ->paginate();

      foreach($expeditionActivityList as $row) {
        $row->jenis_surat_jalan = substr($row->nomor_surat_jalan, 0, 2);
        $exStatusActivity = ExStatusActivity::where('ex_status_activity.ex_id',$row->id)
        ->orderBy('ex_status_activity.updated_at', 'DESC')
        ->select('ex_status_activity.long_lat','ex_status_activity.img', 'ex_status_activity.img_tujuan', 'ex_status_activity.services', 'ex_status_activity.service_charge', 'ex_status_activity.status_destination', 'ex_status_activity.new_address', 'ex_status_activity.extra_price')->first();
        $row->long_lat = $exStatusActivity['long_lat'];
        $row->img = ($exStatusActivity['img']) ? url('uploads/expedition/'.$exStatusActivity['img']) :'';
        $row->img_tujuan = ($exStatusActivity['img_tujuan']) ? url('uploads/expedition/'.$exStatusActivity['img_tujuan']) :'';
        $row->services = $exStatusActivity['services'];
        $row->service_charge = $exStatusActivity['service_charge'];
        $row->status_destination = $exStatusActivity['status_destination'];
        $row->new_address = $exStatusActivity['new_address'];
        $row->extra_price = isset($exStatusActivity['extra_price']) ? $exStatusActivity['extra_price'] : null;
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
                            'ex_master_cabang.cabang_name', 'expedition_activity.harga_ojk', 'expedition_activity.harga_otv',
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
                            'expedition_activity.harga_ojk', 'expedition_activity.harga_otv', 'ex_master_kenek.kenek_name')
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
        ->orderBy('ex_status_activity.created_at', 'DESC')
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
                    ->whereYear('expedition_activity.tgl_po', $data['year'])
                    ->whereMonth('expedition_activity.tgl_po', $data['month'])
                    ->whereIn('expedition_activity.status_activity', ['CLOSED_EXPEDITION', 'WAITING_OWNER'])
                    ->where('expedition_activity.is_deleted', 'false')
                    ->select('expedition_activity.*', 'all_global_param.param_name as status_name',
                            'ex_master_truck.truck_name', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat',
                            'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten',
                            'ex_master_cabang.cabang_name', 'expedition_activity.harga_ojk', 'expedition_activity.harga_otv',
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
      // $factory = new FirebaseService();
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
      // $factory->sendNotif($requests);
  }

}
