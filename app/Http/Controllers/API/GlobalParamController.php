<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GlobalParam;
use Auth;
use DB;
use Validator;

class GlobalParamController extends Controller
{
  public function getList(Request $request) {
      if($request->isMethod('GET')) {
        $data = $request->all();
        $whereField = 'name, email, customer.nama';
        $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
        $globalParamList = GlobalParam::where(function($query) use($whereField, $whereValue) {
                               if($whereValue) {
                                   foreach(explode(', ', $whereField) as $idx => $field) {
                                       $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                                   }
                               }
                           })
                           ->where('status_active', 1)
                           ->orderBy('param_name', 'ASC')
                           ->get();

        $globalParamArr = $globalParamList->groupBy('param_type');
        
        return response()->json([
            'code' => 200,
            'code_message' => 'Success',
            'code_type' => 'Success',
            'data'=> $globalParamArr
        ], 200);
    
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
