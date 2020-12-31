<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;
use Validator;
use App\Models\UserDetail;
use App\Models\GlobalParam;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  public $successStatus = 201;
 
  public function login(){
      $user = User::where('email', request('email'))->first();
      $datas = null;

      if(!isset($user)) {
        return response()->json([
            'code' => 403,
            'code_message' => 'User tidak ditemukan',
            'code_type' => 'BadRequest',
            'data'=> null
        ], 403);
      }

      if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
          if(!Auth::user()->is_active) {
              return response()->json([
                  'code' => 402,
                  'code_message' => 'User tidak aktif',
                  'code_type' => 'BadRequest',
                  'data'=> null
              ], 402);
          }
          
          $schema = Auth::user()->schema.'.';
          $user->remember_token = $user->createToken('nApp')->accessToken;
          $user->id_fcm_android = request('id_fcm_android');
          $user->save();
          $roleAccess = DB::table(Auth::user()->schema.'.usr_group_menu')
                        ->join(Auth::user()->schema.'.usr_menu', 'usr_group_menu.menu_id', 'usr_menu.id')
                        ->select('usr_menu.menu_name as menu_name')
                        ->where('usr_group_menu.group_id', Auth::user()->group_id)
                        ->get();

          $user->group_name = isset(DB::table($schema.'usr_group')->find($user->group_id)->group_name) ? DB::table($schema.'usr_group')->find($user->group_id)->group_name : null;
          foreach($roleAccess as $val) {
               $datas[] = $val->menu_name;
          }

          $user->module_access = $datas;
          return response()->json([
              'code' => 200,
              'code_message' => 'Success',
              'code_type' => 'Success',
              'data'=> $user
          ],
          200);
      
      }else {
          return response()->json([
              'code' => 401,
              'code_message' => 'Username atau password salah',
              'code_type' => 'BadRequest',
              'data'=> null
          ], 401);
      }
  }

  public function register(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'name' => 'required',
          'email' => 'required|email|unique:users',
          'password' => 'required',
          'password_confirmation' => 'required|same:password'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'code' => 401,
          'code_message' => 'Fail',
          'code_type' => 'BadRequest',
          'data'=> null
        ], 401);      
      }

      $input = $request->all();

      unset($input['password_confirmation']);
      unset($input['_token']);
      unset($input['terms']);

      $input['password'] = bcrypt($input['password']);
      $input['group_id'] = $input['group_id'];
      $input['is_active'] = 0;
      $user = User::create($input);
      $success['tokens'] =  $user->createToken('nApp')->accessToken;
      $success['name'] =  $user->name;
      $updateUser = User::find($user->id);
      $updateUser->tokens = $success['tokens'];
      $updateUser->save();
      $user->tokens = $success['tokens'];

      return response()->json([
        'code' => 200,
        'code_message' => 'Success',
        'code_type' => 'Success',
        'data'=> $user
      ], 200);
  }

  public function details()
  {
      $user = Auth::user();
      
      return response()->json([
        'code' => 200,
        'code_message' => 'Success',
        'code_type' => 'Success',
        'data'=> $user
      ], 200);
  }

  public function getList(Request $request) {
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereField = 'name, email, customer.nama';
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $userList = User::where(function($query) use($whereField, $whereValue) {
                           if($whereValue) {
                             foreach(explode(', ', $whereField) as $idx => $field) {
                               $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                             }
                           }
                         })
                         ->select('users.*')
                         ->orderBy('id', 'ASC')
                         ->paginate();
        
        foreach($userList as $row) {
          $row->data_json = $row->toJson();
        }
        
        if(!isset($userList)){
          return response()->json([
            'code' => 404,
            'code_message' => 'Data tidak ditemukan',
            'code_type' => 'BadRequest',
            'data'=> $userList
          ], 404);
        }else{
          return response()->json([
            'code' => 200,
            'code_message' => 'Success',
            'code_type' => 'Success',
            'data'=> $userList
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

  public function upatePassword(Request $request)
  {
    if($request->isMethod('POST')) {
      $user = Auth::user();
      if (Hash::check($request->password_old, $user->password)) { 
        $user->password = bcrypt($request->password);
        if($user->save()){
            return response()->json([
              'code' => 200,
              'code_message' => 'Success',
              'code_type' => 'Success',
            ], 200);
          }else{
            return response()->json([
              'code' => 400,
              'code_message' => 'Fail',
              'code_type' => 'BadRequest',
            ], 400);
          }
        }else{
          return response()->json([
            'code' => 401,
            'code_message' => 'Password lama tidak cocok',
            'code_type' => 'BadRequest',
          ], 401);
        }
        
      }else{
          return response()->json([
            'code' => 405,
            'code_message' => 'Method salah',
            'code_type' => 'BadRequest',
            'data'=> null
        ], 405);
      }
  }

  public function detailProfile(){
    $user = Auth::user();
    $userDetail = UserDetail::where('id_user',$user->id)->first();
    // dd($user->id);
    if(isset($userDetail)){
      $agama = GlobalParam::where('id', $userDetail->agama)->select('description')->first();
      $kelamin = GlobalParam::where('id', $userDetail->jenis_kelamin)->select('description')->first();
      $userDetail->agama = $agama->description;
      $userDetail->jenis_kelamin = $kelamin->description;
      $userDetail->foto_profil = ($userDetail->foto_profil) ? url('uploads/profilephoto/'.$userDetail->foto_profil) :url('uploads/sparepart/nia3.png');
      return response()->json([
        'code' => 200,
        'code_message' => 'Success',
        'code_type' => 'Success',
        'data'=> $userDetail
      ], 200);
    }else{
        return response()->json([
          'code' => 404,
          'code_message' => 'Detail tidak ditemukan',
          'code_type' => 'BadRequest',
          'data'=> null
      ], 404);
    }
  }

  public function updatePhotoProfile(Request $request){
    if($request->isMethod('POST')) {
      $img = $request->file('foto_profil');
      $user = Auth::user();
      $userDetail = UserDetail::where('id_user',$user->id)->first();
      
      $current_date_time = Carbon::now()->toDateTimeString(); 
      if(isset($userDetail)){
        $userDetail->updated_at = $current_date_time;
        $userDetail->updated_by = $user->id;
        if(isset($img)){
          //upload image
          $fileExt = $img->extension();
          $fileName = "IMG-PROFILE-".$userDetail->first_name.".".$fileExt;
          $path = public_path().'/uploads/profilephoto/' ;
          $oldFile = $path.$userDetail->first_name;
 
          $userDetail->foto_profil = $fileName;
          $img->move($path, $fileName);
       }
        if($userDetail->save()){
          return response()->json([
            'code' => 200,
            'code_message' => 'Success',
            'code_type' => 'Success'
          ], 200);
        }else{
          return response()->json([
            'code' => 500,
            'code_message' => 'Fail',
            'code_type' => 'BadRequest',
          ], $this->successStatus);
        }
      }else{
          return response()->json([
            'code' => 404,
            'code_message' => 'User Tidak Ditemukan',
            'code_type' => 'BadRequest',
        ], 404);
      }
    }else{
      return response()->json([
        'code' => 405,
        'code_message' => 'Method salah',
        'code_type' => 'BadRequest',
      ], 405);
    }
  }

}
