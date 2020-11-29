<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\OjkToko;
use Auth;

class OjkTokoController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'name, no_OjkToko';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $ojkTokoList = OjkToko::where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                          foreach(explode(', ', $whereField) as $idx => $field) {
                            $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                          }
                        }
                      })
                      ->orderBy('id', 'ASC')
                      ->paginate();
      
      foreach($ojkTokoList as $row) {
        $row->data_json = $row->toJson();
      }

      return response()->json([
        'status' => true,
        'responses' => $ojkTokoList
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
      $ojkToko = new OjkToko;
      
      $this->validate($request, [
        // 'no_OjkToko' => 'required|string|max:255|unique:OjkToko',
        'name' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $ojkToko->{$key} = $row;
      }

      if($ojkToko->save()){
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
      $ojkToko = OjkToko::find($data['id']);
      
      $this->validate($request, [
        // 'no_OjkToko' => 'required|string|max:255|unique:OjkToko,no_OjkToko,'.$data['id'].',id',
        'name' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $ojkToko->{$key} = $row;
      }

      if($ojkToko->save()){
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
      $ojkToko = OjkToko::find($data['id']);

      if($ojkToko->delete()){
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
