<?php

namespace App\Http\Controllers\API;

use App\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Cabang;
use App\Models\UserDetail;
use App\Models\GlobalParam;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;

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
          $cabang_name = Cabang::find($user->cabang_id)->cabang_name;

          if(!Auth::user()->is_active) {
              return response()->json([
                  'code' => 402,
                  'code_message' => 'User tidak aktif',
                  'code_type' => 'BadRequest',
                  'data'=> null
              ], 402);
          }
          
          $schema = Auth::user()->schema.'.';
          $token = Helper::createJwt($user);
        //   $user->remember_token = $user->createToken('nApp')->accessToken;
          $user->tokens = $token;
          $user->id_fcm_android = request('id_fcm_android');
          $user->save();
          $roleAccess = DB::table(Auth::user()->schema.'.usr_group_menu')
                        ->join(Auth::user()->schema.'.usr_menu', 'usr_group_menu.menu_id', 'usr_menu.id')
                        ->select('usr_menu.menu_name as menu_name')
                        ->where('usr_group_menu.group_id', Auth::user()->group_id)
                        ->get();

          $user->group_name = isset(DB::table($schema.'usr_group')->find($user->group_id)->group_name) ? DB::table($schema.'usr_group')->find($user->group_id)->group_name : null;
          $user->cabang_name = $cabang_name;
          foreach($roleAccess as $val) {
               $datas[] = $val->menu_name;
          }

          $user->module_access = $datas;
          Auth::login($user);
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
      $input['is_deleted'] = 'f';
      
      $user = User::create($input);
      $success['tokens'] =  $user->createToken('nApp')->accessToken;
      $success['name'] =  $user->name;
      $success['is_active'] =  $user->is_active;
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

        if(isset($request->tgl_lahir) && $request->tgl_lahir) {
            $request->tgl_lahir = date('Y-m-d', strtotime($request->tgl_lahir));
        }

        $userDetail->first_name = isset($request->first_name) && $request->first_name ? $request->first_name : $request->name;
        $userDetail->last_name = $request->last_name;
        $userDetail->nomor_hp = $request->nomor_hp;
        $userDetail->jenis_kelamin = $request->jenis_kelamin;
        $userDetail->tgl_lahir = $request->tgl_lahir;
        $userDetail->agama = $request->agama;
        $userDetail->no_rek = $request->no_rek;
        $userDetail->nama_bank = $request->nama_bank;
        $userDetail->nama_rekening = $request->nama_rekening;
        $userDetail->id_user = $user->id;
        $userDetail->created_at = $current_date_time;
        $userDetail->created_by = $userAuth->id;

        // foreach($input as $key => $row) {
        //   $userDetail->{$key} = $row;
        // }

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
    unset($input['first_name']);
    unset($input['last_name']);
    unset($input['nomor_hp']);
    unset($input['jenis_kelamin']);
    unset($input['tgl_lahir']);
    unset($input['agama']);
    unset($input['no_rek']);
    unset($input['nama_bank']);
    unset($input['nama_rekening']);

    foreach($input as $key => $row) {
        if($row) {
            $user->{$key} = $row;
        }
    }

    if($user->save()){
      $userDetail = UserDetail::where('id_user', $input['id'])->first();
      
      if($userDetail) {
          isset($request->first_name) && $request->first_name ? $userDetail->first_name = $request->first_name : null;
          isset($request->last_name) && $request->last_name ? $userDetail->last_name = $request->last_name : null;
          isset($request->nomor_hp) && $request->nomor_hp ? $userDetail->nomor_hp = $request->nomor_hp : null;
          isset($request->jenis_kelamin) && $request->jenis_kelamin ? $userDetail->jenis_kelamin = $request->jenis_kelamin : null;
          isset($request->tgl_lahir) && $request->tgl_lahir ? $userDetail->tgl_lahir = date('Y-m-d', strtotime($request->tgl_lahir)) : null;
          isset($request->agama) && $request->agama ? $userDetail->agama = $request->agama : null;
          isset($request->no_rek) && $request->no_rek ? $userDetail->no_rek = $request->no_rek : null;
          isset($request->nama_bank) && $request->nama_bank ? $userDetail->nama_bank = $request->nama_bank : null;
          isset($request->nama_rekening) && $request->nama_rekening ? $userDetail->nama_rekening = $request->nama_rekening : null;
          $userDetail->id_user = $user->id;
          
        } else {
          $userDetail = new UserDetail;

          isset($request->first_name) && $request->first_name ? $userDetail->first_name = $request->first_name : null;
          isset($request->last_name) && $request->last_name ? $userDetail->last_name = $request->last_name : null;
          isset($request->nomor_hp) && $request->nomor_hp ? $userDetail->nomor_hp = $request->nomor_hp : null;
          isset($request->jenis_kelamin) && $request->jenis_kelamin ? $userDetail->jenis_kelamin = $request->jenis_kelamin : null;
          isset($request->tgl_lahir) && $request->tgl_lahir ? $userDetail->tgl_lahir = $request->tgl_lahir : null;
          isset($request->agama) && $request->agama ? $userDetail->agama = $request->agama : null;
          isset($request->no_rek) && $request->no_rek ? $userDetail->no_rek = $request->no_rek : null;
          isset($request->nama_bank) && $request->nama_bank ? $userDetail->nama_bank = $request->nama_bank : null;
          isset($request->nama_rekening) && $request->nama_rekening ? $userDetail->nama_rekening = $request->nama_rekening : null;
          $userDetail->id_user = $user->id;

      }

      $userDetail->save();

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

  public function editProfile(Request $request) {
    $input = $request->all();
    // $validator = Validator::make($request->all(), [
    //     'name' => 'required',
    //     'email' => 'required|email|unique:users,email,'.$input['id'].',id',
    //     'password' => 'nullable',
    //     'password_confirmation' => 'nullable|same:password'
    // ]);

    $user = Auth::user();

    
    $current_date_time = Carbon::now()->toDateTimeString();
    // if ($validator->fails()) {
    //   return response()->json([
    //     'code' => 401,
    //     'code_message' => 'Fail',
    //     'code_type' => 'BadRequest',
    //     'data'=> null
    //   ], 401);      
    // }

    $userDetail = UserDetail::where('id_user',$user->id)->first();
    unset($input['_token']);
    unset($input['password_confirmation']);
    if(isset($userDetail)){
    foreach($input as $key => $row) {
        if($row) {
            $userDetail->{$key} = $row;
        }
    }

    if($userDetail->save()){
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
      ], 400);
    }  
  }else{
      return response()->json([
      'code' => 400,
      'code_message' => 'Gagal menyimpan user',
      'code_type' => 'BadRequest',
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
        $whereField = 'users.name, users.email, usr_groups.group_name';
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $userList = User::join($request->current_user->schema.'.usr_group as usr_groups', 'users.group_id', 'usr_groups.id')
                    ->leftjoin($request->current_user->schema.'.usr_detail as usr_details', 'users.id', 'usr_details.id_user')
                    ->where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                            foreach(explode(', ', $whereField) as $idx => $field) {
                            $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                            }
                        }
                    })
                    ->where('users.is_deleted', 'false')
                    ->select('users.*', 'usr_groups.group_name', 'usr_details.first_name', 'usr_details.last_name', 'usr_details.alamat', 'usr_details.tgl_lahir', 'usr_details.nomor_hp', 'usr_details.keterangan', 'usr_details.jenis_kelamin', 'usr_details.agama', 'usr_details.id_user', 'usr_details.foto_profil', 'usr_details.no_rek', 'usr_details.nama_bank', 'usr_details.nama_rekening')
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
          'code_message' => 'Harap menghubungi admin untuk perubahan datayo',
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

  public function delete(Request $request) {
    if($request->isMethod('POST')) {
        $data = $request->all();
        $user = User::find($data['id']);
        $current_date_time = Carbon::now()->toDateTimeString(); 
        $user_id = Auth::user()->id;
        $user->deleted_at = $current_date_time;
        $user->deleted_by = $user_id;
        $user->is_deleted = true;
  
  
        if($user->save()){
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
