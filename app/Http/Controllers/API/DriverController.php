<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Driver;
use Auth;

class DriverController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'name, no_Driver';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $driverList = Driver::where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                          foreach(explode(', ', $whereField) as $idx => $field) {
                            $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                          }
                        }
                      })
                      ->orderBy('id', 'ASC')
                      ->paginate();
      
      foreach($driverList as $row) {
        $row->data_json = $row->toJson();
      }

      return response()->json([
        'status' => true,
        'responses' => $driverList
      ], 201);

      
      
    } else {
      return response()->json([
        'status' => false,
        'message' => "<strong>failed') !</strong> method_not_allowed"
      ], 405);
    }
  }

  public function add(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $driver = new Driver;
      
      $this->validate($request, [
        // 'no_Driver' => 'required|string|max:255|unique:Driver',
        'name' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $driver->{$key} = $row;
      }

      if($driver->save()){
        return response()->json([
          'status' => true,
          'message' => 'success'
        ], 201);
      
      } else {
        return response()->json([
          'status' => true,
          'message' => 'gagal'
        ], 405);  
      }
      
    } else {
      return response()->json([
        'status' => false,
        'message' => "<strong>failed') !</strong> method_not_allowed"
      ], 405);
    }
  }

  public function edit(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $driver = Driver::find($data['id']);
      
      $this->validate($request, [
        // 'no_Driver' => 'required|string|max:255|unique:Driver,no_Driver,'.$data['id'].',id',
        'name' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $driver->{$key} = $row;
      }

      if($driver->save()){
        return response()->json([
          'status' => true,
          'message' => 'success'
        ], 201);
      
      } else {
        return response()->json([
          'status' => true,
          'message' => 'gagal'
        ], 405);  
      }

    } else {
      return response()->json([
        'status' => false,
        'message' => "<strong>failed') !</strong> method_not_allowed"
      ], 405);
    }
  }

  public function delete(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $driver = Driver::find($data['id']);

      if($driver->delete()){
        return response()->json([
          'status' => true,
          'message' => 'success'
        ], 201);
      
      } else {
        return response()->json([
          'status' => true,
          'message' => 'gagal'
        ], 405);  
      }
    } else {
      return response()->json([
        'status' => false,
        'message' => "<strong>failed') !</strong> method_not_allowed"
      ], 405);
    }
  }
}
