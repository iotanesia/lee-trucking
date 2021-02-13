<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\UserDetail;
use Auth;
use DB;

class HomeController extends Controller
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
        $schema = Auth::user()->schema;
        $totalEx = DB::select('SELECT COUNT(id) AS total FROM '.$schema.'.expedition_activity');
        $totalClose = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".expedition_activity WHERE status_activity = 'CLOSED_EXPEDITION'");
        $totalOnProggres = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".expedition_activity WHERE status_activity <> 'CLOSED_EXPEDITION'");
        $totalrepair = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".stk_repair_header");
        $totalrepairBan = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".stk_repair_header WHERE kode_repair LIKE '%RPBAN-%'");
        $totalrepairNonBan = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".stk_repair_header WHERE kode_repair LIKE '%RP-%'");
        $totaltruck = DB::select("SELECT COUNT(id) AS total FROM ".$schema.".ex_master_truck");

        $data['total_expedisi'] = $totalEx[0];
        $data['total_on_progress'] = $totalOnProggres[0];
        $data['total_close'] = $totalClose[0];
        $data['total_repair'] = $totalrepair[0];
        $data['total_repairBan'] = $totalrepairBan[0];
        $data['total_repairNonBan'] = $totalrepairNonBan[0];
        $data['total_truck'] = $totaltruck[0];
        // dd($data);
        return view('home', $data);
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

    public function myProfile(Request $request)
    {
        $data['user_detail'] = UserDetail::where('id_user', Auth::user()->id)->first();
        return view('my_profile', $data);
    }

}
