<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\ExpeditionActivity;
use App\Models\Ojk;
use App\Models\Truck;
use App\Models\Kabupaten;
use Auth;

class InvoiceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['title'] = 'Invoice Report';
      
                                                    // dd($data);

        return view('invoice.index', $data);
    }

    public function dataTableInvoiceReport(){
        $data = ExpeditionActivity::leftJoin('ex_master_ojk' ,'expedition_activity.id_ojk','ex_master_ojk.id')
        ->leftJoin('ex_wil_kabupaten','ex_master_ojk.kabupaten_id','ex_wil_kabupaten.id')
        ->leftJoin('ex_master_truck','expedition_activity.truck_id','ex_master_truck.id')
        ->select(DB::raw('count(*) as rit'),'expedition_activity.tgl_po','ex_wil_kabupaten.kabupaten'
                ,'ex_master_truck.truck_plat','expedition_activity.jumlah_palet'
                ,'expedition_activity.toko','expedition_activity.harga_otv')
          ->groupBy('expedition_activity.ojk_id', 'ex_master_truck.truck_plat')->paginate();
       
       return json_decode($data);
    }
    public function exportExcelBO(){
        $startDate = $data['start_date'];
        $endDate = $data['end_date'];
        $data = ExpeditionActivity::leftJoin('ex_master_ojk' ,'expedition_activity.ojk_id','ex_master_ojk.id')
        ->leftJoin('ex_wil_kabupaten','ex_master_ojk.kabupaten_id','ex_wil_kabupaten.id')
        ->leftJoin('ex_master_truck','expedition_activity.truck_id','ex_master_truck.id')
        ->where('expedition_activity.nomor_surat_jalan','iLike','BO%')
        ->select(DB::raw('COUNT("ojk_id") AS rit'),'expedition_activity.tgl_po','ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
                ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
                ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
                ,'expedition_activity.toko','expedition_activity.harga_otv')
                ->whereBetween('expedition_activity.tgl_po', [$startDate, $endDate])
               ->groupBy('expedition_activity.tgl_po','ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
                ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
                ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
                ,'expedition_activity.toko','expedition_activity.harga_otv')->get();
                foreach($data as $row) {
                    $row->harga_per_rit = 'Rp.'. number_format($row->harga_otv, 0, ',', '.');
                    $row->total = 'Rp.'. number_format(($row->rit*$row->harga_otv), 0, ',', '.');
                    
                    $row->data_json = $row->toJson();
                }
        $total_with_photo = UserService::countWithPhoto();
        Excel::create('excelfile', function($excel) use ($users, $total, $total_with_photo) {
            $excel->sheet('Excel', function($sheet) use ($users, $total, $total_with_photo) {
                $sheet->loadView('invoice.excel-bo')->with("users", $users)->with("total", $total)->with("total_with_photo", $total_with_photo);
            });
        })->export('xls');
    }
 
}
