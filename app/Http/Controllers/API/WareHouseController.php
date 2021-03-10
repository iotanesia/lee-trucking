<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoaActivity;
use App\Models\CoaMasterSheet;
use App\Models\SparePart;
use App\Models\StkHistorySparePart;
use App\Models\GlobalParam;
use App\User;
use Validator;
use Auth;
use Carbon\Carbon;
use DB;

class WareHouseController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'sparepart_name, group_name, stk_master_sparepart.barcode_pabrik';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $sparePartList = SparePart::join('stk_master_group_sparepart', 'stk_master_group_sparepart.id',
                                       'stk_master_sparepart.group_sparepart_id')
                       ->where('stk_master_sparepart.is_deleted','=','false')
                       ->where(function($query) use($whereField, $whereValue) {
                           if($whereValue) {
                               foreach(explode(', ', $whereField) as $idx => $field) {
                               $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                               }
                           }
                           })
                       ->where('type', 'GUDANG')
                       ->select('stk_master_sparepart.*', 'stk_master_group_sparepart.group_name')
                       ->orderBy('id', 'ASC')
                       ->paginate();
      
      foreach($sparePartList as $row) {
        $row->img_sparepart = ($row->img_sparepart) ? url('uploads/sparepart/'.$row->img_sparepart) :url('uploads/sparepart/nia3.png');
        $row->data_json = $row->toJson();
      }

      if(!isset($sparePartList)){
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
          'result'=> $sparePartList
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

  public function getListAll(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'sparepart_name, group_name, stk_master_sparepart.barcode_pabrik';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $sparePartList = SparePart::join('stk_master_group_sparepart', 'stk_master_group_sparepart.id',
                                       'stk_master_sparepart.group_sparepart_id')
                       ->join('all_global_param as sparepart_jenis', 'stk_master_sparepart.sparepart_jenis', 'sparepart_jenis.param_code')
                       ->where('stk_master_sparepart.is_deleted','=','false')
                       ->where(function($query) use($whereField, $whereValue) {
                           if($whereValue) {
                               foreach(explode(', ', $whereField) as $idx => $field) {
                               $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                               }
                           }
                           })
                       ->where('type', 'GUDANG')
                       ->where('group_sparepart_id', '<>', 5)
                       ->select('stk_master_sparepart.*', 'stk_master_group_sparepart.group_name')
                       ->orderBy('id', 'ASC')
                       ->get();
      
      foreach($sparePartList as $row) {
        $row->img_sparepart = ($row->img_sparepart) ? url('uploads/sparepart/'.$row->img_sparepart) :url('uploads/sparepart/nia3.png');
        $row->data_json = $row->toJson();
      }

      if(!isset($sparePartList)){
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
          'result'=> $sparePartList
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

  public function getListAllBan(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'sparepart_name, group_name, stk_master_sparepart.barcode_pabrik';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $sparePartList = SparePart::join('stk_master_group_sparepart', 'stk_master_group_sparepart.id',
                                       'stk_master_sparepart.group_sparepart_id')
                       ->join('all_global_param as sparepart_jenis', 'stk_master_sparepart.sparepart_jenis', 'sparepart_jenis.param_code')
                       ->where('stk_master_sparepart.is_deleted','=','false')
                       ->where(function($query) use($whereField, $whereValue) {
                           if($whereValue) {
                               foreach(explode(', ', $whereField) as $idx => $field) {
                               $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                               }
                           }
                           })
                       ->where('type', 'GUDANG')
                       ->where('group_sparepart_id', 5)
                       ->select('stk_master_sparepart.*', 'stk_master_group_sparepart.group_name')
                       ->orderBy('id', 'ASC')
                       ->get();
      
      foreach($sparePartList as $row) {
        $row->img_sparepart = ($row->img_sparepart) ? url('uploads/sparepart/'.$row->img_sparepart) :url('uploads/sparepart/nia3.png');
        $row->data_json = $row->toJson();
      }

      if(!isset($sparePartList)){
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
          'result'=> $sparePartList
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

  public function getListUnpaid(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'sparepart_name, group_name, stk_master_sparepart.barcode_pabrik';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $sparePartList = SparePart::join('stk_master_group_sparepart', 'stk_master_group_sparepart.id',
                                       'stk_master_sparepart.group_sparepart_id')
                       ->with(['stk_history_stok' => function($querys) {
                            $querys->where('sparepart_jenis', 'PURCHASE')
                                   ->where('sparepart_type', 'DEBT');
                            }
                       ])
                       ->join('all_global_param as sparepart_jenis', 'stk_master_sparepart.sparepart_jenis', 'sparepart_jenis.param_code')
                       ->where('stk_master_sparepart.is_deleted','=','false')
                       ->where('stk_master_sparepart.sparepart_type', 'DEBT')
                       ->where('stk_master_sparepart.sparepart_type', 'DEBT')
                       ->where(function($query) use($whereField, $whereValue) {
                           if($whereValue) {
                               foreach(explode(', ', $whereField) as $idx => $field) {
                               $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                               }
                           }
                       })
                       ->where('type', 'GUDANG')
                       ->where('stk_master_sparepart.sparepart_jenis', 'PURCHASE')
                       ->select('stk_master_sparepart.*', 'stk_master_group_sparepart.group_name')
                       ->orderBy('id', 'ASC')
                       ->paginate();
      
      foreach($sparePartList as $row) {
        $row->makeVisible('stk_history_stok');
        $row->img_sparepart = ($row->img_sparepart) ? url('uploads/sparepart/'.$row->img_sparepart) :url('uploads/sparepart/nia3.png');
        $row->data_json = $row->toJson();
      }

      if(!isset($sparePartList)){
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
          'result'=> $sparePartList
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

  public function getListDetailHistory(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'sparepart_name, group_name, stk_master_sparepart.barcode_pabrik';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $sparePartList = StkHistorySparePart::where(function($query) use($whereField, $whereValue) {
                           if($whereValue) {
                               foreach(explode(', ', $whereField) as $idx => $field) {
                               $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                               }
                           }
                       })
                       ->where('sparepart_id', $data['id'])
                       ->select('stk_history_stock.*')
                       ->orderBy('id', 'DESC')
                       ->paginate();
      
      foreach($sparePartList as $row) {
        $row->makeVisible('stk_history_stok');
        $row->img_sparepart = ($row->img_sparepart) ? url('uploads/sparepart/'.$row->img_sparepart) :url('uploads/sparepart/nia3.png');
        $row->data_json = $row->toJson();
      }

      if(!isset($sparePartList)){
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
          'result'=> $sparePartList
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
      $data['amount'] = str_replace('.', '', $data['amount']);
      $img = $request->file('img_sparepart');
      $sparePart = new SparePart;

      DB::connection(Auth::user()->schema)->beginTransaction();

      $validator = Validator::make($request->all(), [
        'sparepart_name' => 'required|string|max:255',
        'sparepart_status' => 'required',
        'jumlah_stok' => 'required',
        'group_sparepart_id' => 'required',
        'barcode_pabrik' => 'required',
        'merk_part' => 'required',
      ]);

      if($validator->fails()){
        return response()->json([
          'code' => 400,
          'code_message' => "Kesalahan dalam penginputan / Inputan kosong",
          'code_type' => 'BadRequest',
        ], 400);

      }else{
        unset($data['_token']);
        unset($data['id']);
        unset($data['no_rek']);

        $current_date_time = Carbon::now()->toDateTimeString(); 
        $user_id = Auth::user()->id;

        foreach($data as $key => $row) {
          $sparePart->{$key} = $row;
          $sparePart->type = 'GUDANG';
        }

        if(isset($img)){
            //upload image
            $fileExt = $img->extension();
            $fileName = "IMG-SPAREPART-".$sparePart->barcode_gudang.".".$fileExt;
            $path = public_path().'/uploads/sparepart/' ;
            $oldFile = $path.$sparePart->barcode_gudang;
   
            $sparePart->img_sparepart = $fileName;
            $img->move($path, $fileName);
         }

        $sparePart->created_at = $current_date_time;
        $sparePart->created_by = $user_id;
       

        if($sparePart->save()){

          DB::connection(Auth::user()->schema)->commit();

          return response()->json([
            'code' => 200,
            'code_message' => 'Berhasil menyimpan data',
            'code_type' => 'Success',
          ], 200);
        
        } else {
          DB::connection(Auth::user()->schema)->rollback();

          return response()->json([
            'code' => 401,
            'code_message' => 'Gagal menyimpan data',
            'code_type' => 'BadRequest',
          ], 401);
        }
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
      $img = $request->file('img_sparepart');
      $sparePart = SparePart::find($data['id']);

    if(isset($data['scanner_form'])) {
        $data['jumlah_stok'] = $data['jumlah_stok'] + $sparePart->jumlah_stok;

    }
      
      unset($data['_token']);
      unset($data['id']);
      unset($data['scanner_form']);
      unset($data['no_rek']);
      
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;

      foreach($data as $key => $row) {
        $sparePart->{$key} = $row;
        $sparePart->type = 'GUDANG';
      }

      if(isset($img)){
         //upload image
         $fileExt = $img->extension();
         $fileName = "IMG-SPAREPART-".$sparePart->barcode_gudang.".".$fileExt;
         $path = public_path().'/uploads/sparepart/' ;
         $oldFile = $path.$sparePart->barcode_gudang;

         $sparePart->img_sparepart = $fileName;
         $img->move($path, $fileName);
      }

      $sparePart->updated_at = $current_date_time;
      $sparePart->updated_by = $user_id;
      $historyStokSparepart = new StkHistorySparePart();

      if($sparePart->save()) {

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
      $sparePart = SparePart::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;

      $sparePart->deleted_at = $current_date_time;
      $sparePart->deleted_by = $user_id;
      $sparePart->is_deleted = true;

      if($sparePart->save()){
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

  public function getListDetail(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'barcode_gudang, barcode_pabrik';
      $whereValue = $data['id'];
      $sparePartList = SparePart::join('stk_master_group_sparepart', 'stk_master_group_sparepart.id',
                                       'stk_master_sparepart.group_sparepart_id')
                       ->where('stk_master_sparepart.is_deleted','=','false')
                       ->select('stk_master_sparepart.*', 'stk_master_group_sparepart.group_name')
                       ->where(function($query) use($whereField, $whereValue) {
                        if($whereValue) {
                          foreach(explode(', ', $whereField) as $idx => $field) {
                            $query->orWhere($field, '=', $whereValue);
                          }
                        }
                      })
                      ->where('type', 'GUDANG')
                      ->first();
      
      if(!isset($sparePartList)){
        return response()->json([
          'code' => 404,
          'code_message' => 'Data tidak ditemukan',
          'code_type' => 'BadRequest',
          'data'=> null
        ], 404);
      }else{
        $sparePartList->img_sparepart = ($sparePartList->img_sparepart) ? url('uploads/sparepart/'.$sparePartList->img_sparepart) : url('uploads/sparepart/nia3.png');
        $sparePartList->data_json = $sparePartList->toJson();
        return response()->json([
          'code' => 200,
          'code_message' => 'Success',
          'code_type' => 'Success',
          'data'=> $sparePartList
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

  public function updateStok(Request $request) {
    if($request->isMethod('POST')) {
        $data = $request->all();
        $img = $request->file('img_sparepart');
        $sparePart = SparePart::find($data['id']);
        $historyStokSparepart = new StkHistorySparePart();
        $data['jumlah_stok'] = $data['jumlah_stok'] + $sparePart->jumlah_stok;

        unset($data['_token']);
        unset($data['id']);
        unset($data['no_rek']);
        
        $current_date_time = Carbon::now()->toDateTimeString(); 
        $user_id = Auth::user()->id;

        foreach($data as $key => $row) {
          $sparePart->{$key} = $row;
        }

        $sparePart->updated_at = $current_date_time;
        $sparePart->updated_by = $user_id;
  
        if($sparePart->save()){
            return response()->json([
              'code' => 401,
              'code_message' => 'Gagal menyimpan data',
              'code_type' => 'BadRequest',
            ], 401);
          
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

  public function paid(Request $request) {
      if($request->isMethod('POST')) {
        $data = $request->all();
        $img = $request->file('img_sparepart');
        $img_paid = $request->file('img');
        $historyStokSparepart = StkHistorySparePart::find($data['id']);

        unset($data['_token']);
        unset($data['id']);
        unset($data['no_rek']);

        if($img_paid) {
            $fileExt = $img_paid->extension();
            $fileName = "IMG-SPAREPART-PAID".$historyStokSparepart->id.'-TSJ-'.date('dmY').".".$fileExt;
            $path =  public_path().'/uploads/sparepart/' ;
        }

        $current_date_time = Carbon::now()->toDateTimeString(); 
        $user_id = Auth::user()->id;
        $historyStokSparepart->sparepart_type = 'PAID_OFF';
        $historyStokSparepart->img_paid = $fileName;
  
        if($historyStokSparepart->save()) {
            if($img_paid) {
                $img_paid->move($path, $fileName);
            }

            $coaMasterSheet = CoaMasterSheet::where('coa_code_sheet', 'ILIKE', '%PL.0007%')->get();
            $sparePartType = GlobalParam::where('param_type', 'SPAREPART_TYPE')->where('param_code', $historyStokSparepart->sparepart_type)->first();

            foreach($coaMasterSheet as $key => $value) {
                $coaActivity = new CoaActivity();
                $coaActivity->activity_id = $sparePartType->id;
                $coaActivity->activity_name = $historyStokSparepart->sparepart_type;
                $coaActivity->status = 'ACTIVE';
                $coaActivity->nominal = $historyStokSparepart->amount;
                $coaActivity->coa_id = $value->id;
                $coaActivity->created_at = $current_date_time;
                $coaActivity->created_by = $user_id;
                $coaActivity->rek_id = $request->no_rek;
                $coaActivity->save();
            }

            $checkUpdate = StkHistorySparePart::where('sparepart_jenis', 'PURCHASE')
                           ->where('sparepart_type', 'DEBT')->where('sparepart_id', $historyStokSparepart->sparepart_id)->count();
            
            if(!$checkUpdate) {
                $sparePart = SparePart::find($historyStokSparepart->sparepart_id);
                $sparePart->sparepart_type = 'PAID_OFF';
                $sparePart->save();
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

}
