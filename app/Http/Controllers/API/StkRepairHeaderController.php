<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\StkRepairHeader;
use App\Models\SparePart;
use App\Models\StkHistorySparePart;
use Auth;
use Carbon\Carbon;

class StkRepairHeaderController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'name, no_StkRepairHeader';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $stkRepairHeader = StkRepairHeader::join('ex_master_truck', 'stk_repair_header.truck_id', 'ex_master_truck.id')
                        ->join('ex_master_driver', 'ex_master_truck.driver_id', 'ex_master_driver.id')
                        ->with(['stk_history_stok'])
                        ->where(function($query) use($whereField, $whereValue) {
                            if($whereValue) {
                                foreach(explode(', ', $whereField) as $idx => $field) {
                                    $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                                }
                            }
                        })
                        ->select('stk_repair_header.*', 'ex_master_truck.truck_plat', 'ex_master_truck.truck_name', 'ex_master_driver.driver_name')
                        ->orderBy('id', 'ASC')
                        ->paginate();
      
      foreach($stkRepairHeader as $row) {
        $row->data_json = $row->toJson();
      }
      
      if(!isset($stkRepairHeader)){
        return response()->json([
          'code' => 404,
          'code_message' => 'Data tidak ditemukan',
          'code_type' => 'BadRequest',
          'result'=> null
        ], 404);
      }else{
        return response()->json([
          'code' => 200,
          'code_message' => 'Success',
          'code_type' => 'Success',
          'result'=> $stkRepairHeader
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

  public function add(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $sparepart_detail = $data['sparepart_detail'];
      $stkRepairHeader = new StkRepairHeader;
      
      $this->validate($request, [
        // 'no_StkRepairHeader' => 'required|string|max:255|unique:StkRepairHeader',
        // 'name' => 'required|string|max:255',
      ]);

      unset($data['_token']);
      unset($data['id']);
      unset($data['sparepart_detail']);

      foreach($data as $key => $row) {
        $stkRepairHeader->{$key} = $row;
        $stkRepairHeader->created_by = Auth::user()->id;
      }
    //   dd($sparepart_detail['sparepart_id']);

      if($stkRepairHeader->save()) {
          if(isset($sparepart_detail)) {
              foreach($sparepart_detail['sparepart_id'] as $key => $row) {
                  $sparepart = SparePart::find($row);
                  $detail = new StkHistorySparePart;
                  $detail->sparepart_name = $sparepart->sparepart_name;
                  $detail->sparepart_status = $sparepart->sparepart_status;
                  $detail->sparepart_jenis = $sparepart->sparepart_jenis;
                  $detail->jumlah_stok = $sparepart_detail['jumlah_stock'][$key];
                  $detail->created_by = Auth::user()->id;
                  $detail->barcode_gudang = $sparepart->barcode_gudang;
                  $detail->barcode_pabrik = $sparepart->barcode_pabrik;
                  $detail->sparepart_type = $sparepart->sparepart_type;
                  $detail->sparepart_id = $row;
                  $detail->amount = $sparepart->amount * $sparepart_detail['jumlah_stock'][$key];
                  $detail->transaction_type = 'OUT';
                  $detail->header_id = $stkRepairHeader->id;
                  $detail->save();
              }
          }
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
      $stkRepairHeader = StkRepairHeader::find($data['id']);
      
      $this->validate($request, [
        // 'no_StkRepairHeader' => 'required|string|max:255|unique:StkRepairHeader,no_StkRepairHeader,'.$data['id'].',id',
        // 'name' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $stkRepairHeader->{$key} = $row;
      }

      if($stkRepairHeader->save()){
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
      $stkRepairHeader = StkRepairHeader::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;
      $stkRepairHeader->deleted_at = $current_date_time;
      $stkRepairHeader->deleted_by = $user_id;
      $stkRepairHeader->is_deleted = true;


      if($stkRepairHeader->save()){
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
