<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\ExpeditionActivity;
use App\Models\Ojk;
use App\Models\Truck;
use App\Models\Kabupaten;
use App\Exports\ExportRepairTruck;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Carbon\Carbon;
use App\Models\StkRepairHeader;

class RepairTruckReportController extends Controller
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
        $data['title'] = 'Repair Truck Report';
        return view('report-repair-truck.index', $data);
    }

    public function dataTableTruckRepairReport(){
        $data = StkRepairHeader::leftJoin('ex_master_truck' ,'stk_repair_header.truck_id','ex_master_truck.id')
          ->where('stk_history_stock.transaction_type','OUT')
          ->select('stk_repair_header.*', 'ex_master_truck.truck_name')
          ->orderBy('stk_repair_header.updated_at','DESC')->paginate();
       
       return json_decode($data);
    }

    public function exportTruckRepair(Request $request){
        $date = $request->dateRangeTruckRepair;
        $startDate = '';
        $endDate = '';
        if(isset($date)){
            $dates = explode('-',$date);
            $startDate = Date('Y-m-d',strtotime($dates[0]));
            $endDate =  Date('Y-m-d',strtotime($dates[1]));
        }
        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        
        $namaFile = 'Repair Truck Report '.Carbon::parse($startDate)->formatLocalized('%d %B %Y').'-'.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportRepairTruck($startDate, $endDate), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }
        
    }

}
