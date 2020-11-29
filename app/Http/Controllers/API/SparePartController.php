<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\SparePart;
use Auth;

class SparePartController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'name, no_SparePart';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $sparePartList = SparePart::where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                          foreach(explode(', ', $whereField) as $idx => $field) {
                            $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                          }
                        }
                      })
                      ->orderBy('id', 'ASC')
                      ->paginate();
      
      foreach($sparePartList as $row) {
        $row->data_json = $row->toJson();
      }

      return response()->json([
        'status' => true,
        'responses' => $sparePartList
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
      $sparePart = new SparePart;
      
      $this->validate($request, [
        // 'no_SparePart' => 'required|string|max:255|unique:SparePart',
        'name' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $sparePart->{$key} = $row;
      }

      if($sparePart->save()){
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
      $sparePart = SparePart::find($data['id']);
      
      $this->validate($request, [
        // 'no_SparePart' => 'required|string|max:255|unique:SparePart,no_SparePart,'.$data['id'].',id',
        'name' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $sparePart->{$key} = $row;
      }

      if($sparePart->save()){
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
      $sparePart = SparePart::find($data['id']);

      if($sparePart->delete()){
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
