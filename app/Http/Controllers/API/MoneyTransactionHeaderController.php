<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\MoneyTransactionHeader;
use Auth;
use Carbon\Carbon;

class MoneyTransactionHeaderController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'MoneyTransactionHeader_plat, MoneyTransactionHeader_name, all_global_param.param_name, ex_master_cabang.cabang_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $moneyTransactionHeaderList = MoneyTransactionHeader::join('public.users', 'users.id', 'money_transaction_header.user_id')->where(function($query) use($whereField, $whereValue) {
                     if($whereValue) {
                       foreach(explode(', ', $whereField) as $idx => $field) {
                         $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                       }
                     }
                   })
                   ->select('money_transaction_header.*', 'users.name as name_user')
                   ->orderBy('money_transaction_header.id', 'ASC')
                   ->paginate();

      foreach($moneyTransactionHeaderList as $row) {
        $row->data_json = $row->toJson();
      }
      
      if(!isset($moneyTransactionHeaderList)){
        return response()->json([
          'code' => 404,
          'code_message' => 'Data tidak ditemukan',
          'code_type' => 'BadRequest',
          'data'=> null
        ], 404);
      }else{
        return response()->json([
          'code' => 200,
          'code_message' => 'Success',
          'code_type' => 'Success',
          'data'=> $moneyTransactionHeaderList
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

  public function add(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $moneyTransactionHeader = new MoneyTransactionHeader;
      
      $this->validate($request, [
        // 'no_MoneyTransactionHeader' => 'required|string|max:255|unique:MoneyTransactionHeader',
        'MoneyTransactionHeader_plat' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $moneyTransactionHeader->{$key} = $row;
      }

      if($moneyTransactionHeader->save()){
        return response()->json([
          'code' => 200,
          'code_message' => 'Berhasil menyimpan data',
          'code_type' => 'Success',
        ], 200);
      
      } else {
        return response()->json([
          'code' => 401,
          'code_message' => 'Gagal menyimpan data',
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

  public function edit(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $moneyTransactionHeader = MoneyTransactionHeader::find($data['id']);
      
      $this->validate($request, [
        // 'no_MoneyTransactionHeader' => 'required|string|max:255|unique:MoneyTransactionHeader,no_MoneyTransactionHeader,'.$data['id'].',id',
        'MoneyTransactionHeader_plat' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $moneyTransactionHeader->{$key} = $row;
      }

      if($moneyTransactionHeader->save()){
        return response()->json([
          'code' => 200,
          'code_message' => 'Berhasil menyimpan data',
          'code_type' => 'Success',
        ], 200);
      
      } else {
        return response()->json([
          'code' => 401,
          'code_message' => 'Gagal menyimpan data',
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

  public function delete(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $moneyTransactionHeader = MoneyTransactionHeader::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;

      $moneyTransactionHeader->deleted_at = $current_date_time;
      $moneyTransactionHeader->deleted_by = $user_id;
      $moneyTransactionHeader->is_deleted = true;


      if($moneyTransactionHeader->save()){
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
