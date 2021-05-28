<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\Cabang;
use App\Models\ExpeditionActivity;
use App\Models\ExStatusActivity;
use App\Models\Kenek;
use App\Models\Driver;
use App\Models\Ban;
use Auth;

class TruckController extends Controller
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
        $data['title'] = 'Truck';
        $data['status'] = GlobalParam::where('param_type', 'TRUCK_STATUS')->get();
        $data['truck_type'] = GlobalParam::where('param_type', 'TRUCK_TYPE')->get();
        $data['cabangList'] = Cabang::where('is_deleted', 'false')->get();
        $data['driverList'] = Driver::where('is_deleted', 'false')->get();
        return view('master.truck.index', $data);
    }

    public function indexTenan(Request $request)
    {
        return view('tenan');
    }

    public function indexTrx(Request $request)
    {
        $customerList = Customer::all();
        $tenanList = Tenan::all();
        return view('transaksi', compact('customerList', 'tenanList'));
    }

    public function detail(Request $request, $id) {
        $data['title'] = 'Ban Truck';
        $data['truck'] = Truck::select('ex_master_truck.*', 'ex_master_cabang.*')->join('ex_master_cabang', 'ex_master_cabang.id', 'ex_master_truck.cabang_id')->where('ex_master_truck.id', $id)->first();
        $data['ban'] = Ban::select('*')->where('truck_id', $id)->get();
        // dd($data);

        foreach($data['ban'] as $key => $val) {
            $val->data_json = $val->toJson();
        }

        return view('master.truck.detail', $data);
    }

}
