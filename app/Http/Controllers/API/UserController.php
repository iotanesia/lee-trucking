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
      $input = $request->all();
      $validator = Validator::make($request->all(), [
          'name' => 'required',
          'email' => 'required|email|unique:users',
          'password' => 'required',
          'password_confirmation' => 'required|same:password'
      ]);

      $userAuth = Auth::user();
      $current_date_time = Carbon::now()->toDateTimeString();
      if ($validator->fails()) {
        return response()->json([
          'code' => 401,
          'code_message' => 'Fail',
          'code_type' => 'BadRequest',
          'data'=> null
        ], 401);      
      }

      unset($input['password_confirmation']);
      unset($input['_token']);
      unset($input['terms']);

      $input['password'] = bcrypt($input['password']);
      $input['group_id'] = $request->group_id;
      
      $user = User::create($input);
      $success['tokens'] =  $user->createToken('nApp')->accessToken;
      $success['name'] =  $user->name;
      $updateUser = User::find($user->id);
      $updateUser->tokens = $success['tokens'];

      if($updateUser->save()){
        $userDetail = new UserDetail();
        
        unset($input['name']);
        unset($input['email']);
        unset($input['password']);
        unset($input['password_confirmation']);
        unset($input['group_id']);
        unset($input['is_active']);
        unset($input['id']);

        // foreach($input as $key => $row) {
        //   $userDetail->{$key} = $row;
        // }
        $userDetail->first_name = $request->name;
        $userDetail->id_user = $user->id;
        $userDetail->created_at = $current_date_time;
        $userDetail->created_by = $userAuth->id;

        if($userDetail->save()) {
          return response()->json([
            'code' => 200,
            'code_message' => 'Success',
            'code_type' => 'Success',
            'data'=> $user
          ], 200);

        }else {
          return response()->json([
            'code' => 400,
            'code_message' => 'Gagal menyimpan user',
            'code_type' => 'BadRequest',
            'data'=> null
          ], 400);
        }

      }else{
          return response()->json([
          'code' => 400,
          'code_message' => 'Gagal menyimpan user',
          'code_type' => 'BadRequest',
          'data'=> null
        ], 400);
      }       
  }

  public function edit(Request $request)
  {
      $input = $request->all();
      $validator = Validator::make($request->all(), [
          'name' => 'required',
          'email' => 'required|email|unique:users,email,'.$input['id'].',id',
          'password' => 'nullable',
          'password_confirmation' => 'nullable|same:password'
      ]);

      if(isset($input['password'])) {
        $input['password'] = bcrypt($input['password']);
      }
      
      $userAuth = Auth::user();
      $current_date_time = Carbon::now()->toDateTimeString();
      if ($validator->fails()) {
        return response()->json([
          'code' => 401,
          'code_message' => 'Fail',
          'code_type' => 'BadRequest',
          'data'=> null
        ], 401);      
      }

      $user = User::find($input['id']);
      
      unset($input['_token']);
      unset($input['password_confirmation']);
  
      foreach($input as $key => $row) {
          if($row) {
              $user->{$key} = $row;
          }
      }

      if($user->save()){
        return response()->json([
            'code' => 200,
            'code_message' => 'Berhasil menyimpan data',
            'code_type' => 'Success',
          ], 200);

      }else{
          return response()->json([
          'code' => 400,
          'code_message' => 'Gagal menyimpan user',
          'code_type' => 'BadRequest',
          'data'=> null
        ], 400);
      }       
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
        $whereField = 'name, email, usr_groups.group_name';
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $userList = User::join(Auth::user()->schema.'.usr_group as usr_groups', 'users.group_id', 'usr_groups.id')
                    ->where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                            foreach(explode(', ', $whereField) as $idx => $field) {
                            $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                            }
                        }
                    })
                    ->select('users.*', 'usr_groups.group_name')
                    ->orderBy('users.id', 'ASC')
                    ->paginate();
        
        foreach($userList as $row) {
          $row->data_json = $row->toJson();
        }
        
        if(!isset($userList)){
          return response()->json([
            'code' => 404,
            'code_message' => 'Data tidak ditemukan',
            'code_type' => 'BadRequest',
            'result'=> $userList
          ], 404);
        }else{
          return response()->json([
            'code' => 200,
            'code_message' => 'Success',
            'code_type' => 'Success',
            'result'=> $userList
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



  public function updatePassword(Request $request)
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
      $agama = GlobalParam::where('param_code', $userDetail->agama)->select('description')->first();
      $kelamin = GlobalParam::where('param_code', $userDetail->jenis_kelamin)->select('description')->first();
      $userDetail->agama = isset($agama) ? $agama->description : null;
      $userDetail->jenis_kelamin = isset($kelamin) ? $kelamin->description : null;
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
          $fileName = "IMG-PROFILE-".$userDetail->first_name."-".$userDetail->id.".".$fileExt;
          $path = public_path().'/uploads/profilephoto/' ;
          $oldFile = $path.$userDetail->first_name."-".$userDetail->id;
 
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
  
  public function updateFcm(Request $request){
    if($request->isMethod('POST')) {
      $user = Auth::user();
      $userDetail = User::where('id',$user->id)->first();
      
      $current_date_time = Carbon::now()->toDateTimeString(); 
      if(isset($userDetail)){
        $userDetail->updated_at = $current_date_time;
        $userDetail->id_fcm_android = $request->id_fcm;
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
