<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Coa;
use Auth;

class CoaController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'name, no_coa';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $coaList = Coa::where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                          foreach(explode(', ', $whereField) as $idx => $field) {
                            $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                          }
                        }
                      })
                      ->orderBy('id', 'ASC')
                      ->paginate();
      
      foreach($coaList as $row) {
        $row->data_json = $row->toJson();
      }

      return response()->json([
        'status' => true,
        'responses' => $coaList
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
      $coa = new Coa;
      
      $this->validate($request, [
        // 'no_coa' => 'required|string|max:255|unique:Coa',
        'name' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $coa->{$key} = $row;
      }

      if($coa->save()){
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
      $coa = Coa::find($data['id']);
      
      $this->validate($request, [
        // 'no_coa' => 'required|string|max:255|unique:Coa,no_coa,'.$data['id'].',id',
        'name' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $coa->{$key} = $row;
      }

      if($coa->save()){
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
      $coa = Coa::find($data['id']);

      if($coa->delete()){
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
