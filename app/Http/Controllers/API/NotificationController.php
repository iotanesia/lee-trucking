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
}