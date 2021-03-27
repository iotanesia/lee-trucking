<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\StkRepairHeader;
use App\Models\SparePart;
use App\Models\StkHistorySparePart;
use App\Models\StkGroupSparepart;
use Auth;
use Carbon\Carbon;
use DB;

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
                        ->where('kode_repair', 'LIKE', '%RP-%')
                        ->select('stk_repair_header.*', 'ex_master_truck.truck_plat', 'ex_master_truck.truck_name', 'ex_master_driver.driver_name')
                        ->orderBy('id', 'ASC')
                        ->paginate();
      foreach($stkRepairHeader as $row) {
        foreach($row->stk_history_stok as $historyStok){
          $merk_part = '';
          $idGroup = null;
          if(isset($historyStok->sparepart_id)){
            $sparePart = SparePart::where('id', $historyStok->sparepart_id)->select('merk_part','group_sparepart_id')->first();
            $merk_part = $sparePart->merk_part;
            $idGroup = $sparePart->group_sparepart_id;
            // dd($sparePart);
          }
          $group_name = '';
          if(isset($idGroup)){
            $groupSparepart = StkGroupSparepart::where('id', $idGroup)->select('group_name')->first();
            $group_name = $groupSparepart->group_name;
          }
          $historyStok->merk_part = $merk_part;
          $historyStok->group_name = $group_name;
        }
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

  public function getListByDriver(Request $request) {
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
                        ->where('ex_master_driver.user_id', Auth::user()->id)
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

      DB::connection(Auth::user()->schema)->beginTransaction();

      foreach($data as $key => $row) {
        $stkRepairHeader->{$key} = $row;
        $stkRepairHeader->created_by = Auth::user()->id;
      }
    //   dd($sparepart_detail['sparepart_id']);

      if($stkRepairHeader->save()) {
          $code = str_repeat("0", 4 - strlen($stkRepairHeader->id)).$stkRepairHeader->id;
          $codes = 'RP-'.date('Ymd').$code;
          $stkRepairHeader->kode_repair = $codes;
          $stkRepairHeader->save();

          if(isset($sparepart_detail)) {
              foreach($sparepart_detail['sparepart_id'] as $key => $row) {
                  $sparepart = SparePart::find($row);

                  if($sparepart->jumlah_stok < $sparepart_detail['jumlah_stock'][$key]) {
                      DB::connection(Auth::user()->schema)->rollback();
                      return response()->json([
                          'code' => 401,
                          'code_message' => 'Stok Tidak mencukupi',
                          'code_type' => 'BadRequest',
                      ], 401);
                  }

                  $sparepart->jumlah_stok = $sparepart->jumlah_stok - $sparepart_detail['jumlah_stock'][$key];
                  $sparepart->save();
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
                  $detail->satuan_type = $sparepart->satuan_type;
                  $detail->save();
              }
          }

          DB::connection(Auth::user()->schema)->commit();
          return response()->json([
          'code' => 200,
          'code_message' => 'Berhasil menyimpan data',
          'code_type' => 'Success',
          ], 200);
      
      } else {
        DB::connection(Auth::user()->schema)->rollback();
        return response()->json([
          'code' => 400,
          'code_message' => 'Gagal menyimpan data',
          'code_type' => 'BadRequest',
        ], 400);
      }
      
    } else {
      return response()->json([
        'code' => 405,
        'code_message' => 'Method salah',
        'code_type' => 'BadRequest',
      ], 405);
    }
  }

  public function addMobile(Request $request) {
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

      DB::connection(Auth::user()->schema)->beginTransaction();

      foreach($data as $key => $row) {
        $stkRepairHeader->{$key} = $row;
        $stkRepairHeader->created_by = Auth::user()->id;
      }
    //   dd($sparepart_detail['sparepart_id']);

      if($stkRepairHeader->save()) {
          $code = str_repeat("0", 4 - strlen($stkRepairHeader->id)).$stkRepairHeader->id;
          $codes = 'RP-'.date('Ymd').$code;
          $stkRepairHeader->kode_repair = $codes;
          $stkRepairHeader->save();

          if(isset($sparepart_detail)) {
              foreach($sparepart_detail['sparepart_id'] as $key => $row) {
                  $sparepart = SparePart::find($row);

                  if($sparepart->jumlah_stok < $sparepart_detail['jumlah_stock'][$key]) {
                      DB::connection(Auth::user()->schema)->rollback();
                      return response()->json([
                          'code' => 401,
                          'code_message' => 'Stok Tidak mencukupi',
                          'code_type' => 'BadRequest',
                      ], 401);
                  }

                  $sparepart->jumlah_stok = $sparepart->jumlah_stok - $sparepart_detail['jumlah_stock'][$key];
                  $sparepart->save();
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
                  $detail->satuan_type = $sparepart->satuan_type;
                  $detail->save();
              }
          }

          DB::connection(Auth::user()->schema)->commit();
          return response()->json([
          'code' => 200,
          'code_message' => 'Berhasil menyimpan data',
          'code_type' => 'Success',
          ], 200);
      
      } else {
        DB::connection(Auth::user()->schema)->rollback();
        return response()->json([
          'code' => 400,
          'code_message' => 'Gagal menyimpan data',
          'code_type' => 'BadRequest',
        ], 400);
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
      $sparepart_detail = $data['sparepart_detail'];
      $stkRepairHeader = StkRepairHeader::find($data['id']);
      
      $this->validate($request, [
        // 'no_StkRepairHeader' => 'required|string|max:255|unique:StkRepairHeader,no_StkRepairHeader,'.$data['id'].',id',
        // 'name' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      unset($data['sparepart_detail']);
      
      foreach($data as $key => $row) {
        $stkRepairHeader->{$key} = $row;
        $stkRepairHeader->created_by = Auth::user()->id;
      }

      if($stkRepairHeader->save()){
        if(isset($sparepart_detail)) {
// dd($sparepart_detail);
            foreach($sparepart_detail['sparepart_id'] as $key => $row) {
                $sparepart = SparePart::find($row);

                if(isset($sparepart_detail['id'][$key])) {
                    $detail = StkHistorySparePart::find($sparepart_detail['id'][$key]);
                    
                } else {
                    $detail = new StkHistorySparePart;
                }

                if(isset($sparepart_detail['is_deleted'][$key]) && $sparepart_detail['is_deleted'][$key] == 1) {
                    $detail->delete();

                } else {
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
                    $detail->satuan_type = $sparepart->satuan_type;
                    $detail->save();
                }
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

  public function editMobile(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $sparepart_detail = $data['sparepart_detail'];
      $stkRepairHeader = StkRepairHeader::find($data['id']);
      
      $this->validate($request, [
        // 'no_StkRepairHeader' => 'required|string|max:255|unique:StkRepairHeader,no_StkRepairHeader,'.$data['id'].',id',
        // 'name' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      unset($data['sparepart_detail']);
      
      foreach($data as $key => $row) {
        $stkRepairHeader->{$key} = $row;
        $stkRepairHeader->created_by = Auth::user()->id;
      }

      if($stkRepairHeader->save()){
        if(isset($sparepart_detail)) {
// dd($sparepart_detail);
            foreach($sparepart_detail['sparepart_id'] as $key => $row) {
                $sparepart = SparePart::find($row);

                if(isset($sparepart_detail['id'][$key])) {
                    $detail = StkHistorySparePart::find($sparepart_detail['id'][$key]);
                    
                } else {
                    $detail = new StkHistorySparePart;
                }

                if(isset($sparepart_detail['is_deleted'][$key]) && $sparepart_detail['is_deleted'][$key] == 1) {
                    $detail->delete();

                } else {
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
                    $detail->satuan_type = $sparepart->satuan_type;
                    $detail->save();
                }
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

  public function delete(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $detail = StkHistorySparePart::where('header_id', $data['id'])->delete();
      $stkRepairHeader = StkRepairHeader::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString();


      if($stkRepairHeader->delete()){
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
