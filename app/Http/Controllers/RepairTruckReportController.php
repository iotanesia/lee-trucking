<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\ExpeditionActivity;
use App\Models\Ojk;
use App\Models\Truck;
use App\Models\Kabupaten;
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

}
