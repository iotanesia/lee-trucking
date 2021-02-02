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

class NotificationController extends Controller
{
    public function getList(Request $request) {
      if($request->isMethod('GET')) {
        $user = Auth::user();
        $data = $request->all();
        $notificationList = Notification::
                        where('notification.id_user_to',$user->id)
                      ->orWhere('notification.id_group',$user->group_id)
                      ->select('notification.*')
                      ->orderBy('created_at', 'desc')
                      ->paginate();
        
        foreach($notificationList as $row) {
          $row->data_json = $row->toJson();
        }
  
        if(!isset($notificationList)){
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
            'result'=> $notificationList
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

    public function isRead(Request $request){
        if($request->isMethod('POST')) {
            $data = $request->all();
            $notification = Notification::find($data['id']);
            $notification->is_read = true;
            if($notification->save()){
              return response()->json([
                'code' => 200,
                'code_message' => 'Success',
                'code_type' => 'Success'
              ], 200);
            }else{
              return response()->json([
                'code' => 404,
                'code_message' => 'Failed',
                'code_type' => 'BadRequest',
                'result'=> null
              ], 404);
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
    
    public function readAll(Request $request){
      if($request->isMethod('POST')) {
          $data = $request->all();
          $userLogin = Auth::user();
          $notificationList = Notification::where('id_user_to',$userLogin->id)->get();
          foreach($notificationList as $notif){
            $notif->is_read = true;
            $notif->save();
          }
          return response()->json([
            'code' => 200,
            'code_message' => 'Success',
            'code_type' => 'Success'
          ], 200);
        } else {
          return response()->json([
            'code' => 405,
            'code_message' => 'Method salah',
            'code_type' => 'BadRequest',
          ], 405);
      }
    }

    public function delete(Request $request){
      if($request->isMethod('POST')) {
          $data = $request->all();
          $notification = Notification::find($data['id']);
          if($notification->delete()){
            return response()->json([
              'code' => 200,
              'code_message' => 'Success',
              'code_type' => 'Success'
            ], 200);
          }else{
            return response()->json([
              'code' => 404,
              'code_message' => 'Failed',
              'code_type' => 'BadRequest',
              'result'=> null
            ], 404);
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

    public function getCount(Request $request){
      $data['count'] = 0;
      if($request->isMethod('POST')) {
          $data = $request->all();
          $userLogin = Auth::user();

         $notificationList = Notification::where('id_user_to',$userLogin->id)->get();
         $data['count'] = isset($notificationList) ? $notificationList->count() : 0;
          if($notificationList->count() > 0){
            return response()->json([
              'code' => 200,
              'code_message' => 'Success',
              'code_type' => 'Success',
               $data
            ], 200);
          }else{
            return response()->json([
              'code' => 404,
              'code_message' => 'Failed',
              'code_type' => 'BadRequest',
              $data
            ], 404);
          }
        } else {
          return response()->json([
            'code' => 405,
            'code_message' => 'Method salah',
            'code_type' => 'BadRequest',
             $data
          ], 405);
      }
    }
}