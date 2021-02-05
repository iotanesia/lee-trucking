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
use Carbon\Carbon;

class MoneyTransactionHeaderController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'MoneyTransactionHeader_plat, MoneyTransactionHeader_name, all_global_param.param_name, ex_master_cabang.cabang_name';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $moneyTransactionHeaderList = MoneyTransactionHeader::join('public.users', 'users.id', 'money_transaction_header.user_id')
                                    ->with(['money_detail_termin'])
                                    ->leftjoin('coa_master_rekening', 'coa_master_rekening.id', 'money_transaction_header.rek_id') 
                                    ->where(function($query) use($whereField, $whereValue) {
                                        if($whereValue) {
                                        foreach(explode(', ', $whereField) as $idx => $field) {
                                            $query->orWhere($field, 'iLIKE', "%".$whereValue."%");
                                        }
                                        }
                                    })
                                    ->where('category_name', 'PINJAMAN_KARYAWAN')
                                    ->select('money_transaction_header.*', 'users.name as name_user', 'coa_master_rekening.rek_no', 'coa_master_rekening.rek_name')
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
        $moneyTransactionHeader->category_name = 'PINJAMAN_KARYAWAN';
        $moneyTransactionHeader->sisa_pokok = $data['pokok'];
        $moneyTransactionHeader->created_by = Auth::user()->id;
      }

      if($moneyTransactionHeader->save()) {
          $coaMasterSheet = CoaMasterSheet::whereIn('coa_code_sheet', ['PL.0003.01', 'PL.0003.02', 'PL.0003.03', 'PL.0003.04'])->get();
          
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
