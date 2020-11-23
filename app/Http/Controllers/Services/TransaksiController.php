<?php
namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Transaksi;
use Auth;

class TransaksiController extends Controller
{
  public function getList(Request $request) {
    if($request->isMethod('GET')) {
      $data = $request->all();
      $whereField = 'no_trx, tenan.nama, customer.nama';
      $whereValue = (isset($data['where_value'])) ? $data['where_value'] : '';
      $transaksiList = Transaksi::leftJoin('customer', 'transaksi.id_customer', 'customer.id')
                       ->leftJoin('tenan', 'transaksi.id_tenan', 'tenan.id')
                       ->where(function($query) use($whereField, $whereValue) {
                         if($whereValue) {
                           foreach(explode(', ', $whereField) as $idx => $field) {
                             $query->orWhere($field, 'LIKE', "%".$whereValue."%");
                           }
                         }
                       })
                       ->select('transaksi.*', 'tenan.nama AS nama_tenan', 'customer.nama AS nama_customer')
                       ->orderBy('id', 'ASC')
                       ->paginate();
      
      foreach($transaksiList as $row) {
        $row->data_json = $row->toJson();
      }

      return response()->json([
        'status' => true,
        'responses' => $transaksiList
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
      $transaksi = new Transaksi;
      
      $this->validate($request, [
        'no_trx' => 'required|string|max:255|unique:transaksi',
      ]);

      unset($data['_token']);
      unset($data['id']);

      $image = $request->file('photo');

      if($image) {
        $fileExtension = $image->getClientOriginalExtension();
        $filename = $data['no_trx'].".".$fileExtension;
        $destinationPath = public_path('/uploads/photo');
        $data['photo'] = $filename;
      }

      foreach($data as $key => $row) {
        $transaksi->{$key} = $row;
      }

      if($transaksi->save()){
        if($request->hasFile('photo') && $request->file('photo')->isValid()) $image->move($destinationPath, $filename);

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
      $transaksi = Transaksi::find($data['id']);
      
      $this->validate($request, [
        'no_trx' => 'required|string|max:255|unique:transaksi,no_trx,'.$data['id'].',id',
      ]);
      
      unset($data['_token']);
      unset($data['id']);

      $image = $request->file('photo');

      if($image) {
        $fileExtension = $image->getClientOriginalExtension();
        $filename = $data['no_trx'].".".$fileExtension;
        $destinationPath = public_path('/uploads/photo');
        $data['photo'] = $filename;
      }
      
      foreach($data as $key => $row) {
        $transaksi->{$key} = $row;
      }

      if($transaksi->save()){
        if($request->hasFile('photo') && $request->file('photo')->isValid()) $image->move($destinationPath, $filename);

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
      $transaksi = Transaksi::find($data['id']);

      if($transaksi->delete()){
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
