<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\Cabang;
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
        $data['cabangList'] = Cabang::all();
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

}