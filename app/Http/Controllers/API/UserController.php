<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Validator;

class UserController extends Controller
{
  public $successStatus = 200;
 
  public function login(){
      if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
          $user = Auth::user();
          $success['token'] =  $user->createToken('My Token')->accessToken;

          return response()->json([
              'data' => $user,
              'success' => $success],
          $this->successStatus);
      }
      else{
          return response()->json(['error'=>'Unauthorised'], 401);
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
          return response()->json(['error'=>$validator->errors()], 401);            
      }

      $input = $request->all();
      $user = new User;

      unset($input['password_confirmation']);

      foreach($input as $key => $val) {
          $user->{$key} = $val;
          $user->password = bcrypt($input['password']);
      }

      $user->save();
      $user->api_token = $user->createToken('nApp')->accessToken;

      return response()->json(['success'=>$user], $this->successStatus);
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
  
        return response()->json([
          'status' => true,
          'responses' => $userList
        ], 201);
  
        
        
      } else {
        return response()->json([
          'status' => false,
          'message' => "<strong>failed') !</strong> method_not_allowed"
        ], 405);
      }

  }
}
