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
use Illuminate\Support\Facades\DB;
use DNS1D;

class SparePartController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $cekRole = $this->checkRoles($request);
      $ids = null;

      if($cekRole) {
        $ids = json_decode($cekRole, true);
      }

      $data = $request->all();
      $whereField = 'sparepart_name, group_name, stk_master_sparepart.barcode_pabrik';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $sparePartList = SparePart::join('stk_master_group_sparepart', 'stk_master_group_sparepart.id',
                                       'stk_master_sparepart.group_sparepart_id')
                       ->join('all_global_param as sparepart_jenis', 'stk_master_sparepart.sparepart_jenis', 'sparepart_jenis.param_code')
                       ->join('public.users', 'users.id', 'stk_master_sparepart.created_by')
                       ->where('stk_master_sparepart.is_deleted','=','false')
                       ->where(function($query) use($whereField, $whereValue) {
                           if($whereValue) {
                               foreach(explode(', ', $whereField) as $idx => $field) {
                               $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                               }
                           }
                       })
                       ->where(function($query) use($ids) {
                        if($ids) {
                           $query->whereIn('users.cabang_id', $ids);
                        }
                       })
                       ->where('type', 'SPAREPART')
                       ->select('stk_master_sparepart.*', 'stk_master_group_sparepart.group_name')
                       ->orderBy('stk_master_sparepart.id', 'DESC')
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
    $cekRole = $this->checkRoles($request);
    $ids = null;

    if($cekRole) {
      $ids = json_decode($cekRole, true);
    }

    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'sparepart_name, group_name, stk_master_sparepart.barcode_pabrik';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $sparePartList = SparePart::join('stk_master_group_sparepart', 'stk_master_group_sparepart.id',
                                       'stk_master_sparepart.group_sparepart_id')
                       ->join('all_global_param as sparepart_jenis', 'stk_master_sparepart.sparepart_jenis', 'sparepart_jenis.param_code')
                       ->join('public.users', 'users.id', 'stk_master_sparepart.created_by')
                       ->where('stk_master_sparepart.is_deleted','=','false')
                       ->where(function($query) use($whereField, $whereValue) {
                           if($whereValue) {
                               foreach(explode(', ', $whereField) as $idx => $field) {
                               $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                               }
                           }
                           })
                        ->where(function($query) use($ids) {
                            if($ids) {
                                $query->whereIn('users.cabang_id', $ids);
                            }
                        })
                       ->where('type', 'SPAREPART')
                       ->where('group_sparepart_id', '<>', 5)
                       ->select('stk_master_sparepart.*', 'stk_master_group_sparepart.group_name')
                       ->orderBy('stk_master_sparepart.id', 'DESC')
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
                       ->where('type', 'SPAREPART')
                       ->where('group_sparepart_id', 5)
                       ->select('stk_master_sparepart.*', 'stk_master_group_sparepart.group_name')
                       ->orderBy('stk_master_sparepart.id', 'DESC')
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
      $cekRole = $this->checkRoles($request);
      $ids = null;

      if($cekRole) {
        $ids = json_decode($cekRole, true);
      }

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
                       ->join('public.users', 'users.id', 'stk_master_sparepart.created_by')
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
                       ->where(function($query) use($ids) {
                            if($ids) {
                            $query->whereIn('users.cabang_id', $ids);
                            }
                       })
                       ->where('type', 'SPAREPART')
                       ->where('stk_master_sparepart.sparepart_jenis', 'PURCHASE')
                       ->select('stk_master_sparepart.*', 'stk_master_group_sparepart.group_name')
                       ->orderBy('stk_master_sparepart.id', 'DESC')
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
      $img = $request->file('img_sparepart');
      $sparePart = new SparePart;

      DB::connection($request->current_user->schema)->beginTransaction();

      $validator = Validator::make($request->all(), [
        'sparepart_name' => 'required|string|max:255',
        'sparepart_status' => 'required',
        'sparepart_jenis' => 'required',
        'jumlah_stok' => 'nullable',
        'group_sparepart_id' => 'required',
        'barcode_pabrik' => 'nullable',
        'sparepart_type' => 'nullable',
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
        unset($data['img_sparepart']);

        $current_date_time = Carbon::now()->toDateTimeString(); 
        $user_id = $request->current_user->id;

        if(isset($img)){
            //upload image
            $fileExt = $img->extension();
            $fileName = "IMG-SPAREPART-".$sparePart->barcode_gudang.".".$fileExt;
            $path = public_path().'/uploads/sparepart/';
            $sparePart->img_sparepart = $fileName;
            $img->move($path, $fileName);
        }
        $data['purchase_date'] = date('Y-m-d', strtotime($data['purchase_date']));
        foreach($data as $key => $row) {
          $sparePart->{$key} = $row;
          $sparePart->type = 'SPAREPART';
        }

        $sparePart->created_at = $current_date_time;
        $sparePart->created_by = $user_id;

        if($sparePart->save()){
          $historyStokSparepart = new StkHistorySparePart();
          $historyStokSparepart->sparepart_name = $sparePart->sparepart_name;
          $historyStokSparepart->sparepart_status = $sparePart->sparepart_status;
          $historyStokSparepart->sparepart_jenis = $sparePart->sparepart_jenis;
          $historyStokSparepart->restok_group_sparepart_id = $sparePart->group_sparepart_id;
          $historyStokSparepart->jumlah_stok = $request->jumlah_stok;
          $historyStokSparepart->created_by = $sparePart->created_by;
          $historyStokSparepart->updated_by = $sparePart->updated_by;
          $historyStokSparepart->deleted_by = $sparePart->deleted_by;
          $historyStokSparepart->deleted_at = $sparePart->deleted_at;
          $historyStokSparepart->is_deleted = $sparePart->is_deleted;
          $historyStokSparepart->img_sparepart = $sparePart->img_sparepart;
          $historyStokSparepart->barcode_gudang = $sparePart->barcode_gudang;
          $historyStokSparepart->barcode_pabrik = $sparePart->barcode_pabrik;
          $historyStokSparepart->sparepart_type = $sparePart->sparepart_type;
          $historyStokSparepart->sparepart_id = $sparePart->id;
          $historyStokSparepart->amount = $sparePart->amount;
          $historyStokSparepart->purchase_date = $sparePart->purchase_date;
          $historyStokSparepart->due_date = $sparePart->due_date;
          $historyStokSparepart->satuan_type = $sparePart->satuan_type;
          $historyStokSparepart->transaction_type = "IN";
          $historyStokSparepart->save();
          $sparePart->barcode_gudang = $sparePart->id.'-TSJ-'.date('dmY');
          $sparePart->save();
          
          if(isset($request->no_rek) && $request->no_rek) {
            $coaMasterSheet = CoaMasterSheet::where('coa_code_sheet', 'ILIKE', '%PL.0007%')->get();
            $sparePartType = GlobalParam::where('param_type', 'SPAREPART_TYPE')->where('param_code', $sparePart->sparepart_type)->first();

            foreach($coaMasterSheet as $key => $value) {
                $coaActivity = new CoaActivity();
                $coaActivity->activity_id = $sparePartType->id;
                $coaActivity->activity_name = $sparePart->sparepart_type;
                $coaActivity->status = 'ACTIVE';
                $coaActivity->nominal = $sparePart->amount;
                $coaActivity->coa_id = $value->id;
                $coaActivity->created_at = $current_date_time;
                $coaActivity->created_by = $user_id;
                $coaActivity->rek_id = $request->no_rek;
                $coaActivity->save();
            }  
          }

          DB::connection($request->current_user->schema)->commit();

          return response()->json([
            'code' => 200,
            'code_message' => 'Berhasil menyimpan data',
            'code_type' => 'Success',
          ], 200);
        
        } else {
          DB::connection($request->current_user->schema)->rollback();

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
      $user_id = $request->current_user->id;

      foreach($data as $key => $row) {
        $sparePart->{$key} = $row;
        $sparePart->type = 'SPAREPART';
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
        if(isset($request->scanner_form)) {
            $historyStokSparepart->sparepart_name = $sparePart->sparepart_name;
            $historyStokSparepart->sparepart_status = $sparePart->sparepart_status;
            $historyStokSparepart->sparepart_jenis = $sparePart->sparepart_jenis;
            $historyStokSparepart->restok_group_sparepart_id = $sparePart->group_sparepart_id;
            $historyStokSparepart->jumlah_stok = $request->jumlah_stok;
            $historyStokSparepart->created_by = $sparePart->created_by;
            $historyStokSparepart->updated_by = $sparePart->updated_by;
            $historyStokSparepart->deleted_by = $sparePart->deleted_by;
            $historyStokSparepart->deleted_at = $sparePart->deleted_at;
            $historyStokSparepart->is_deleted = $sparePart->is_deleted;
            $historyStokSparepart->img_sparepart = $sparePart->img_sparepart;
            $historyStokSparepart->barcode_gudang = $sparePart->barcode_gudang;
            $historyStokSparepart->barcode_pabrik = $sparePart->barcode_pabrik;
            $historyStokSparepart->sparepart_type = $sparePart->sparepart_type;
            $historyStokSparepart->sparepart_id = $sparePart->id;
            $historyStokSparepart->amount = $sparePart->amount;
            $historyStokSparepart->purchase_date = $sparePart->purchase_date;
            $historyStokSparepart->due_date = $sparePart->due_date;
            $historyStokSparepart->satuan_type = $sparePart->satuan_type;
            $historyStokSparepart->transaction_type = "IN";
            $historyStokSparepart->save();

            if(isset($request->no_rek) && $request->no_rek) {

                $coaMasterSheet = CoaMasterSheet::where('coa_code_sheet', 'ILIKE', '%PL.0007%')->get();
                $sparePartType = GlobalParam::where('param_type', 'SPAREPART_TYPE')->where('param_code', $sparePart->sparepart_type)->first();

                foreach($coaMasterSheet as $key => $value) {
                    $coaActivity = new CoaActivity();
                    $coaActivity->activity_id = $sparePartType->id;
                    $coaActivity->activity_name = $sparePart->sparepart_type;
                    $coaActivity->status = 'ACTIVE';
                    $coaActivity->nominal = $sparePart->amount;
                    $coaActivity->coa_id = $value->id;
                    $coaActivity->created_at = $current_date_time;
                    $coaActivity->created_by = $user_id;
                    $coaActivity->rek_id = $request->no_rek;
                    $coaActivity->save();
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
      $sparePart = SparePart::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = $request->current_user->id;

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
                      ->first();
      
      if(!isset($sparePartList)){
        return response()->json([
          'code' => 404,
          'code_message' => 'Data tidak ditemukan',
          'code_type' => 'BadRequest',
          'data'=> null
        ], 404);
      }else{
        $sparePartList->img_sparepart = ($sparePartList->img_sparepart) ? url('uploads/sparepart/'.$sparePartList->img_sparepart) :url('uploads/sparepart/nia3.png');
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
        $user_id = $request->current_user->id;

        foreach($data as $key => $row) {
          $sparePart->{$key} = $row;
        }

        $sparePart->updated_at = $current_date_time;
        $sparePart->updated_by = $user_id;
  
        if($sparePart->save()){
          $historyStokSparepart->sparepart_name = $sparePart->sparepart_name;
          $historyStokSparepart->sparepart_status = $sparePart->sparepart_status;
          $historyStokSparepart->sparepart_jenis = $sparePart->sparepart_jenis;
          $historyStokSparepart->restok_group_sparepart_id = $sparePart->group_sparepart_id;
          $historyStokSparepart->jumlah_stok = $sparePart->jumlah_stok;
          $historyStokSparepart->created_by = $sparePart->created_by;
          $historyStokSparepart->updated_by = $sparePart->updated_by;
          $historyStokSparepart->deleted_by = $sparePart->deleted_by;
          $historyStokSparepart->deleted_at = $sparePart->deleted_at;
          $historyStokSparepart->is_deleted = $sparePart->is_deleted;
          $historyStokSparepart->img_sparepart = $sparePart->img_sparepart;
          $historyStokSparepart->barcode_gudang = $sparePart->barcode_gudang;
          $historyStokSparepart->barcode_pabrik = $sparePart->barcode_pabrik;
          $historyStokSparepart->sparepart_type = $sparePart->sparepart_type;
          $historyStokSparepart->sparepart_id = $sparePart->id;
          $historyStokSparepart->amount = $sparePart->amount;
          $historyStokSparepart->purchase_date = $sparePart->purchase_date;
          $historyStokSparepart->due_date = $sparePart->due_date;
          $historyStokSparepart->satuan_type = $sparePart->satuan_type;
          $historyStokSparepart->transaction_type = "IN";

          if(isset($request->no_rek) && $request->no_rek) {
                $coaMasterSheet = CoaMasterSheet::where('coa_code_sheet', 'ILIKE', '%PL.0007%')->get();
                $sparePartType = GlobalParam::where('param_type', 'SPAREPART_TYPE')->where('param_code', $sparePart->sparepart_type)->first();

                foreach($coaMasterSheet as $key => $value) {
                    $coaActivity = new CoaActivity();
                    $coaActivity->activity_id = $sparePartType->id;
                    $coaActivity->activity_name = $sparePart->sparepart_type;
                    $coaActivity->status = 'ACTIVE';
                    $coaActivity->nominal = $sparePart->amount;
                    $coaActivity->coa_id = $value->id;
                    $coaActivity->created_at = $current_date_time;
                    $coaActivity->created_by = $user_id;
                    $coaActivity->rek_id = $request->no_rek;
                    $coaActivity->save();
                }
          }

          if($historyStokSparepart->save()){
            return response()->json([
              'code' => 200,
              'code_message' => 'Berhasil menyimpan data',
              'code_type' => 'Success',
            ], 200);
          }else{
            return response()->json([
              'code' => 401,
              'code_message' => 'Gagal menyimpan data',
              'code_type' => 'BadRequest',
            ], 401);
          }
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
            $historyStokSparepart->img_paid = $fileName;
        }

        $current_date_time = Carbon::now()->toDateTimeString(); 
        $user_id = $request->current_user->id;
        $historyStokSparepart->sparepart_type = 'PAID_OFF';
  
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

  public function barcode(Request $request) {
      $data = $request->all();
      return DNS1D::getBarcodeSVG($data['id'], 'C39');
  }

}
