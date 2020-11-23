<?php
namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Tenan;
use Auth;

class TenanController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'nama, no_tenan';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $tenanList = Tenan::where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                          foreach(explode(', ', $whereField) as $idx => $field) {
                            $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                          }
                        }
                      })
                      ->orderBy('id', 'ASC')
                      ->paginate();
      
      foreach($tenanList as $row) {
        $row->data_json = $row->toJson();
      }

      return response()->json([
        'status' => true,
        'responses' => $tenanList
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
      $tenan = new Tenan;
      
      $this->validate($request, [
        'no_tenan' => 'required|string|max:255|unique:tenan',
        'nama' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $tenan->{$key} = $row;
      }

      if($tenan->save()){
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
      $tenan = Tenan::find($data['id']);
      
      $this->validate($request, [
        'no_tenan' => 'required|string|max:255|unique:tenan,no_tenan,'.$data['id'].',id',
        'nama' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $tenan->{$key} = $row;
      }

      if($tenan->save()){
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
      $tenan = Tenan::find($data['id']);

      if($tenan->delete()){
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
