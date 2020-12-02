<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;
use Validator;

class UserController extends Controller
{
  public $successStatus = 201;
 
  public function login(){
      $user = User::leftjoin('group', 'users.id_group', 'group.id')->select('users.*', 'group.name as group_name')
      ->where('email', request('email'))->first();

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

          $roleAccess = DB::table('group_access')
                        ->join('menu', 'group_access.id_menu', 'menu.id')
                        ->join('group', 'group_access.id_group', 'group.id')
                        ->select('menu.name as menu_name')
                        ->where('id_group', Auth::user()->id_group)
                        ->get();

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
          $this->successStatus);
      
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
      $user = new User;

      unset($input['password_confirmation']);

      foreach($input as $key => $val) {
          $user->{$key} = $val;
          $user->tokens = $user->createToken('nApp')->accessToken;
          $user->password = bcrypt($input['password']);
          $user->is_active = 0;
      }

      $user->save();

      return response()->json([
        'code' => 201,
        'code_message' => 'Success',
        'code_type' => 'Success',
        'data'=> $user
      ], $this->successStatus);
  }

  public function details()
  {
      $user = Auth::user();
      return response()->json(['success' => $user], $this->successStatus);
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
}
