<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\MoneyTransactionHeader;
use App\Models\MoneyDetailTermin;
use App\Models\CoaActivity;
use App\Models\CoaMasterSheet;
use Auth;
use DB;
use Carbon\Carbon;

class MoneyTransactionHeaderController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $cekRole = $this->checkRoles();
      $ids = null;
  
      if($cekRole) {
          $ids = json_decode($cekRole, true);
      }
      $data = $request->all();
      $whereField = 'users.name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $moneyTransactionHeaderList = MoneyTransactionHeader::join('public.users', 'users.id', 'money_transaction_header.user_id')
                                    ->with(['money_detail_termin' => function($query){ 
                                        $query->leftJoin('coa_master_rekening', 'coa_master_rekening.id', 'money_detail_termin.rek_id')->select('money_detail_termin.*', 'coa_master_rekening.rek_name', 'coa_master_rekening.rek_no',  'coa_master_rekening.id');
                                    }])
                                    ->leftjoin('coa_master_rekening', 'coa_master_rekening.id', 'money_transaction_header.rek_id') 
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
                                    ->where('category_name', 'PINJAMAN_KARYAWAN')
                                    ->select('money_transaction_header.user_id', 'users.name as name_user', DB::raw('SUM(pokok) AS pokok'), DB::raw('SUM(sisa_pokok) AS sisa_pokok'), 
                                    DB::raw("CASE WHEN SUM(sisa_pokok) = 0 THEN 'LUNAS'ELSE 'BELUM_LUNAS' END AS status"))
                                    ->groupBy('money_transaction_header.user_id', 'users.name')
                                    ->orderBy('users.name', 'ASC')
                                    ->paginate();

                                    // , 'money_transaction_header.status as statue', 'users.name as name_user', 'coa_master_rekening.rek_no as rek_no', 'coa_master_rekening.rek_name as rek_name',

      foreach($moneyTransactionHeaderList as $row) {
        $row->total_bayar = count($row->money_detail_termin);
        $row->data_json = $row->toJson();
      }
      
      if(!isset($moneyTransactionHeaderList)){
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
          'result'=> $moneyTransactionHeaderList
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

  public function getListDetailPembayaran(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'money_transaction_header.category_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $moneyTransactionHeaderList = MoneyDetailTermin::leftJoin('coa_master_rekening', 'coa_master_rekening.id', 'money_detail_termin.rek_id')
                                    ->where('transaksi_header_id', $data['transaksi_header_id'])
                                    ->select('money_detail_termin.*', 'coa_master_rekening.rek_name', 'coa_master_rekening.rek_no', 'coa_master_rekening.id')
                                    ->orderBy('money_detail_termin.id', 'DESC')
                                    ->paginate();

      foreach($moneyTransactionHeaderList as $row) {
        $row->data_json = $row->toJson();
      }
      
      if(!isset($moneyTransactionHeaderList)){
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
          'result'=> $moneyTransactionHeaderList
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

  public function getListDetail(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'money_transaction_header.category_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $moneyTransactionHeaderList = MoneyTransactionHeader::join('public.users', 'users.id', 'money_transaction_header.user_id')
                                    ->with(['money_detail_termin' => function($query){ 
                                        $query->leftJoin('coa_master_rekening', 'coa_master_rekening.id', 'money_detail_termin.rek_id')->select('money_detail_termin.*', 'coa_master_rekening.rek_name', 'coa_master_rekening.rek_no',  'coa_master_rekening.id');
                                    }])
                                    ->leftjoin('coa_master_rekening', 'coa_master_rekening.id', 'money_transaction_header.rek_id') 
                                    ->where(function($query) use($whereField, $whereValue) {
                                        if($whereValue) {
                                            foreach(explode(', ', $whereField) as $idx => $field) {
                                                $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                                            }
                                        }
                                    })
                                    ->where('user_id', $data['user_id'])
                                    ->where('category_name', 'PINJAMAN_KARYAWAN')
                                    ->select('money_transaction_header.*', 'money_transaction_header.status', 'users.name as name_user', 'coa_master_rekening.rek_no', 'coa_master_rekening.rek_name')
                                    ->orderBy('money_transaction_header.id', 'ASC')
                                    ->paginate();

      foreach($moneyTransactionHeaderList as $row) {
        $row->total_bayar = count($row->money_detail_termin);
        $row->data_json = $row->toJson();
      }
      
      if(!isset($moneyTransactionHeaderList)){
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
          'result'=> $moneyTransactionHeaderList
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
      $whereField = 'money_transaction_header.category_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $moneyTransactionHeaderList = MoneyTransactionHeader::join('public.users', 'users.id', 'money_transaction_header.user_id')
                                    ->with(['money_detail_termin' => function($query){ 
                                        $query->leftJoin('coa_master_rekening', 'coa_master_rekening.id', 'money_detail_termin.rek_id')->select('money_detail_termin.*', 'coa_master_rekening.rek_name', 'coa_master_rekening.rek_no',  'coa_master_rekening.id');
                                    }])
                                    ->leftjoin('coa_master_rekening', 'coa_master_rekening.id', 'money_transaction_header.rek_id') 
                                    ->where(function($query) use($whereField, $whereValue) {
                                        if($whereValue) {
                                            foreach(explode(', ', $whereField) as $idx => $field) {
                                                $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                                            }
                                        }
                                    })
                                    ->where('user_id', Auth::user()->id)
                                    ->where('category_name', 'PINJAMAN_KARYAWAN')
                                    ->select('money_transaction_header.*', 'users.name as name_user', 'coa_master_rekening.rek_no', 'coa_master_rekening.rek_name')
                                    ->orderBy('money_transaction_header.id', 'ASC')
                                    ->paginate();

      foreach($moneyTransactionHeaderList as $row) {
        $row->total_bayar = count($row->money_detail_termin);
        $row->data_json = $row->toJson();
      }
      
      if(!isset($moneyTransactionHeaderList)){
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
          'result'=> $moneyTransactionHeaderList
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

  public function getListUangKeluar(Request $request) {
    if($request->isMethod('GET')) {
      $cekRole = $this->checkRoles();
      $ids = null;

      if($cekRole) {
          $ids = json_decode($cekRole, true);
      }
      $data = $request->all();
      $whereField = 'money_transaction_header.category_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $moneyTransactionHeaderList = MoneyTransactionHeader::with(['money_detail_termin' => function($query){ 
                                        $query->leftJoin('coa_master_rekening', 'coa_master_rekening.id', 'money_detail_termin.rek_id')->select('money_detail_termin.*', 'coa_master_rekening.rek_name', 'coa_master_rekening.rek_no',  'coa_master_rekening.id');
                                    }])
                                    ->leftjoin('coa_master_rekening', 'coa_master_rekening.id', 'money_transaction_header.rek_id') 
                                    ->where(function($query) use($whereField, $whereValue) {
                                        if($whereValue) {
                                            foreach(explode(', ', $whereField) as $idx => $field) {
                                                $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                                            }
                                        }
                                    })
                                    ->whereIn('category_name', ['DANA_LAIN_LAIN', 'GAJI_KARYAWAN'])
                                    ->select('money_transaction_header.*', 'coa_master_rekening.rek_no', 'coa_master_rekening.rek_name')
                                    ->orderBy('money_transaction_header.id', 'DESC')
                                    ->paginate();

      foreach($moneyTransactionHeaderList as $row) {
        $row->total_bayar = count($row->money_detail_termin);
        $row->data_json = $row->toJson();
      }
      
      if(!isset($moneyTransactionHeaderList)){
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
          'result'=> $moneyTransactionHeaderList
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

  public function getListModalUsaha(Request $request) {
    if($request->isMethod('GET')) {
      $cekRole = $this->checkRoles();
      $ids = null;

      if($cekRole) {
          $ids = json_decode($cekRole, true);
      }
      $data = $request->all();
      $whereField = 'money_transaction_header.category_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $moneyTransactionHeaderList = MoneyTransactionHeader::join('public.users', 'users.id', 'money_transaction_header.user_id')
                                    ->with(['money_detail_termin' => function($query){ 
                                        $query->leftJoin('coa_master_rekening', 'coa_master_rekening.id', 'money_detail_termin.rek_id')->select('money_detail_termin.*', 'coa_master_rekening.rek_name', 'coa_master_rekening.rek_no',  'coa_master_rekening.id');
                                    }])
                                    ->leftjoin('coa_master_rekening', 'coa_master_rekening.id', 'money_transaction_header.rek_id') 
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
                                    ->where('category_name', 'MODAL_USAHA')
                                    ->select('money_transaction_header.*', 'users.name as name_user', 'coa_master_rekening.rek_no', 'coa_master_rekening.rek_name')
                                    ->orderBy('money_transaction_header.id', 'ASC')
                                    ->paginate();

      foreach($moneyTransactionHeaderList as $row) {
        $row->total_bayar = count($row->money_detail_termin);
        $row->data_json = $row->toJson();
      }
      
      if(!isset($moneyTransactionHeaderList)){
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
          'result'=> $moneyTransactionHeaderList
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

  public function addModalUsaha(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $moneyTransactionHeader = new MoneyTransactionHeader;
      $current_date_time = Carbon::now()->toDateTimeString();
      $user_id = Auth::user()->id;
      
      $this->validate($request, [
        // 'no_MoneyTransactionHeader' => 'required|string|max:255|unique:MoneyTransactionHeader',
        // 'MoneyTransactionHeader_plat' => 'required|string|max:255',
      ]);
    
      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $moneyTransactionHeader->{$key} = $row;
        $moneyTransactionHeader->status = 'BELUM_LUNAS';
        $moneyTransactionHeader->category_name = 'MODAL_USAHA';
        $moneyTransactionHeader->sisa_pokok = $data['pokok'];
        $moneyTransactionHeader->created_by = Auth::user()->id;
      }

      if($moneyTransactionHeader->save()) {
          $coaMasterSheet = CoaMasterSheet::whereIn('coa_code_sheet', ['PL.0008.06', 'PL.0008.07'])->get();
          
        //   for ($i=0; $i < $moneyTransactionHeader->termin; $i++) { 
        //       $moneyDetailTermin = new MoneyDetailTermin;
        //       $moneyDetailTermin->baris_termin = 1;
        //       $moneyDetailTermin->nominal_termin = $moneyTransactionHeader->pokok / $moneyTransactionHeader->termin;
        //       $moneyDetailTermin->transaksi_header_id = $moneyTransactionHeader->id;
        //       $moneyDetailTermin->created_by = Auth::user()->id;
        //       $moneyDetailTermin->save();
        //   }

          foreach($coaMasterSheet as $key => $value) {
            $coaActivity = new CoaActivity();
            $coaActivity->activity_id = 52;
            $coaActivity->activity_name = 'PINJAMAN_KARYAWAN';
            $coaActivity->status = 'ACTIVE';
            $coaActivity->nominal = $moneyTransactionHeader->pokok;
            $coaActivity->coa_id = $value->id;
            $coaActivity->created_at = $current_date_time;
            $coaActivity->created_by = $user_id;
            $coaActivity->rek_id = $request->no_rek;
            $coaActivity->table = 'money_detail_termin';
            $coaActivity->table_id = $moneyTransactionHeader->id;
            $coaActivity->save();
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

  public function add(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $moneyTransactionHeader = new MoneyTransactionHeader;
      $current_date_time = Carbon::now()->toDateTimeString();
      $user_id = Auth::user()->id;

      if($data['date']) {
          $data['date'] = date('Y-m-d', strtotime($data['date']));
      }

      if(isset($data['category_name']) && $data['category_name']) {
          $data['category_name'] = $data['category_name'];

      } else {
          $data['category_name'] = 'PINJAMAN_KARYAWAN';
      }

      $this->validate($request, [
        // 'no_MoneyTransactionHeader' => 'required|string|max:255|unique:MoneyTransactionHeader',
        // 'MoneyTransactionHeader_plat' => 'required|string|max:255',
      ]);
    
      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $moneyTransactionHeader->{$key} = $row;
        $moneyTransactionHeader->status = 'BELUM_LUNAS';
        // $moneyTransactionHeader->category_name = 'PINJAMAN_KARYAWAN';
        $moneyTransactionHeader->sisa_pokok = $data['pokok'];
        $moneyTransactionHeader->created_by = Auth::user()->id;
      }

      if($moneyTransactionHeader->save()) {     
          if(isset($data['category_name']) && $data['category_name'] == 'DANA_LAIN_LAIN') {
              $coaMasterSheet = CoaMasterSheet::whereIn('coa_code_sheet', ['PL.0001.04', 'PL.0001.05', 'PL.0001.06', 'PL.0001.07'])->get();

          } elseif(isset($data['category_name']) && $data['category_name'] == 'GAJI_KARYAWAN') {
              $coaMasterSheet = CoaMasterSheet::whereIn('coa_code_sheet', ['PL.0001.17', 'PL.0001.18', 'PL.0001.19', 'PL.0001.07'])->get();

          } else {
              $coaMasterSheet = CoaMasterSheet::whereIn('coa_code_sheet', ['PL.0003.01', 'PL.0003.02', 'PL.0003.03', 'PL.0003.04'])->get();
          }
          
        //   for ($i=0; $i < $moneyTransactionHeader->termin; $i++) { 
        //       $moneyDetailTermin = new MoneyDetailTermin;
        //       $moneyDetailTermin->baris_termin = 1;
        //       $moneyDetailTermin->nominal_termin = $moneyTransactionHeader->pokok / $moneyTransactionHeader->termin;
        //       $moneyDetailTermin->transaksi_header_id = $moneyTransactionHeader->id;
        //       $moneyDetailTermin->created_by = Auth::user()->id;
        //       $moneyDetailTermin->save();
        //   }

          foreach($coaMasterSheet as $key => $value) {
            $coaActivity = new CoaActivity();
            $coaActivity->activity_id = 52;
            $coaActivity->activity_name = isset($data['category_name']) ? $data['category_name'] :'PINJAMAN_KARYAWAN';
            $coaActivity->status = 'ACTIVE';
            $coaActivity->nominal = $moneyTransactionHeader->pokok;
            $coaActivity->coa_id = $value->id;
            $coaActivity->created_at = $data['date'];
            $coaActivity->created_by = $user_id;
            $coaActivity->rek_id = $request->no_rek;
            $coaActivity->table = 'money_transaction_header';
            $coaActivity->table_id = $moneyTransactionHeader->id;
            $coaActivity->save();
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
      $current_date_time = Carbon::now()->toDateTimeString();
      $user_id = Auth::user()->id;
      $moneyTransactionHeader = MoneyTransactionHeader::find($data['id']);
      $coaActivity = CoaActivity::where('table', 'money_transaction_header')->where('table_id', $data['id'])->delete();
      
      $this->validate($request, [
        // 'no_MoneyTransactionHeader' => 'required|string|max:255|unique:MoneyTransactionHeader,no_MoneyTransactionHeader,'.$data['id'].',id',
        // 'MoneyTransactionHeader_plat' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);

      foreach($data as $key => $row) {
        $moneyTransactionHeader->{$key} = $row;
        $moneyTransactionHeader->sisa_pokok = $data['pokok'];
      }

      if($moneyTransactionHeader->save()) {
        //   $moneyDetailTermin = MoneyDetailTermin::where('transaksi_header_id', $moneyTransactionHeader->id)->delete();
          $coaMasterSheet = CoaMasterSheet::whereIn('coa_code_sheet', ['PL.0003.01', 'PL.0003.02', 'PL.0003.03', 'PL.0003.04'])->get();
          
        //   for ($i=0; $i < $moneyTransactionHeader->termin; $i++) { 
        //     $moneyDetailTermin = new MoneyDetailTermin;
        //     $moneyDetailTermin->baris_termin = 1;
        //     $moneyDetailTermin->nominal_termin = $moneyTransactionHeader->pokok / $moneyTransactionHeader->termin;
        //     $moneyDetailTermin->transaksi_header_id = $moneyTransactionHeader->id;
        //     $moneyDetailTermin->created_by = Auth::user()->id;
        //     $moneyDetailTermin->save();
        //   }

          foreach($coaMasterSheet as $key => $value) {
            $coaActivity = new CoaActivity();
            $coaActivity->activity_id = 52;
            $coaActivity->activity_name = isset($data['category_name']) ? $data['category_name'] :'PINJAMAN_KARYAWAN';
            $coaActivity->status = 'ACTIVE';
            $coaActivity->nominal = $moneyTransactionHeader->pokok;
            $coaActivity->coa_id = $value->id;
            $coaActivity->created_at = $current_date_time;
            $coaActivity->created_by = $user_id;
            $coaActivity->rek_id = $request->no_rek;
            $coaActivity->table = 'money_transaction_header';
            $coaActivity->table_id = $moneyTransactionHeader->id;
            $coaActivity->save();
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

  public function editModalUsaha(Request $request) {
    if($request->isMethod('POST')) {
      $data = $request->all();
      $current_date_time = Carbon::now()->toDateTimeString();
      $user_id = Auth::user()->id;
      $moneyTransactionHeader = MoneyTransactionHeader::find($data['id']);
      $coaActivity = CoaActivity::where('table', 'money_transaction_header')->where('table_id', $data['id'])->delete();
      
      $this->validate($request, [
        // 'no_MoneyTransactionHeader' => 'required|string|max:255|unique:MoneyTransactionHeader,no_MoneyTransactionHeader,'.$data['id'].',id',
        // 'MoneyTransactionHeader_plat' => 'required|string|max:255',
      ]);
      
      unset($data['_token']);
      unset($data['id']);
      
      foreach($data as $key => $row) {
        $moneyTransactionHeader->{$key} = $row;
        $moneyTransactionHeader->sisa_pokok = $data['pokok'];
      }

      if($moneyTransactionHeader->save()) {
        //   $moneyDetailTermin = MoneyDetailTermin::where('transaksi_header_id', $moneyTransactionHeader->id)->delete();
          $coaMasterSheet = CoaMasterSheet::whereIn('coa_code_sheet', ['PL.0008.06', 'PL.0008.07'])->get();
          
        //   for ($i=0; $i < $moneyTransactionHeader->termin; $i++) { 
        //     $moneyDetailTermin = new MoneyDetailTermin;
        //     $moneyDetailTermin->baris_termin = 1;
        //     $moneyDetailTermin->nominal_termin = $moneyTransactionHeader->pokok / $moneyTransactionHeader->termin;
        //     $moneyDetailTermin->transaksi_header_id = $moneyTransactionHeader->id;
        //     $moneyDetailTermin->created_by = Auth::user()->id;
        //     $moneyDetailTermin->save();
        //   }

          foreach($coaMasterSheet as $key => $value) {
            $coaActivity = new CoaActivity();
            $coaActivity->activity_id = 52;
            $coaActivity->activity_name = 'MODAL_USAHA';
            $coaActivity->status = 'ACTIVE';
            $coaActivity->nominal = $moneyTransactionHeader->pokok;
            $coaActivity->coa_id = $value->id;
            $coaActivity->created_at = $current_date_time;
            $coaActivity->created_by = $user_id;
            $coaActivity->rek_id = $request->no_rek;
            $coaActivity->table = 'money_detail_termin';
            $coaActivity->table_id = $moneyTransactionHeader->id;
            $coaActivity->save();
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

  public function paid(Request $request) {
      if($request->isMethod('POST')) {
          $data = $request->all();
          $moneyDetailTermin = new MoneyDetailTermin;
          $moneyTransactionHeader = MoneyTransactionHeader::find($data['transaksi_header_id']);
          $checkBarisLast = MoneyDetailTermin::where('transaksi_header_id', $data['transaksi_header_id'])->orderBy('id', 'DESC')->first();
          $current_date_time = Carbon::now()->toDateTimeString();

          if($data['date']) {
              $data['date'] = date('Y-m-d', strtotime($data['date']));
          }
          $user_id = Auth::user()->id;
          
          if($checkBarisLast) {
              $baris_termin = $checkBarisLast->baris_termin + 1;
          
          } else {
              $baris_termin = 1;
          }

          unset($data['_token']);
          unset($data['id']);

          foreach($data as $key => $row) {
              $moneyDetailTermin->{$key} = $row;
              $moneyDetailTermin->baris_termin = $baris_termin;
          }

          if($moneyDetailTermin->save()) {
              $coaMasterSheet = CoaMasterSheet::whereIn('coa_code_sheet', ['PL.0003.05', 'PL.0003.06', 'PL.0003.07', 'PL.0003.08'])->get();
              $moneyTransactionHeader->sisa_pokok = $moneyTransactionHeader->sisa_pokok - $moneyDetailTermin->nominal_termin;

              if($moneyTransactionHeader->sisa_pokok <= 0 ) {
                  $moneyTransactionHeader->status = 'LUNAS';  
              }

              $moneyTransactionHeader->save();

              foreach($coaMasterSheet as $key => $value) {
                  $coaActivity = new CoaActivity();
                  $coaActivity->activity_id = 52;
                  $coaActivity->activity_name = 'PINJAMAN_KARYAWAN';
                  $coaActivity->status = 'ACTIVE';
                  $coaActivity->nominal = $moneyTransactionHeader->pokok;
                  $coaActivity->coa_id = $value->id;
                  $coaActivity->created_at = $current_date_time;
                  $coaActivity->created_by = $user_id;
                  $coaActivity->rek_id = $request->rek_id;
                  $coaActivity->table = 'money_detail_termin';
                  $coaActivity->table_id = $moneyTransactionHeader->id;
                  $coaActivity->save();
              }

              return response()->json([
                'code' => 200,
                'code_message' => 'Berhasil menyimpan data',
                'code_type' => 'Success',
              ], 200);
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
      $coaActivityDelete = CoaActivity::where('table', 'money_detail_termin')->where('table_id', $data['id'])->delete();
      $MoneyDetailTermin = MoneyDetailTermin::where('transaksi_header_id', $data['id'])->delete();
      $moneyTransactionHeader = MoneyTransactionHeader::find($data['id']);
      $current_date_time = Carbon::now()->toDateTimeString(); 
      $user_id = Auth::user()->id;

    //   $moneyTransactionHeader->deleted_at = $current_date_time;
    //   $moneyTransactionHeader->deleted_by = $user_id;
    //   $moneyTransactionHeader->is_deleted = true;


      if($moneyTransactionHeader->delete()){
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
