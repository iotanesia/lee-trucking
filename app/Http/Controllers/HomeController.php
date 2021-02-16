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

        $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $ex = DB::select("SELECT date_part('month', updated_at) AS months, COUNT(id) FROM ".$schema.".expedition_activity GROUP BY months");
        $truck = DB::select("SELECT b.cabang_name, COUNT(a.id) FROM ".$schema.".ex_master_truck AS a JOIN ".$schema.".ex_master_cabang AS b ON a.cabang_id = b.id GROUP BY cabang_id, b.cabang_name");

        foreach($truck as $key => $val) {
            $cabangName = strtolower(str_replace(" ", "_", $val->cabang_name));
            $data[$cabangName] = $val->count;
        }
        
        foreach($ex as $key => $row) {
            $row->months = $month[($row->months - 1)];
            $data['bulan'][] = $row->months;
            $data['total'][] = $row->count;
        }

        foreach($truck as $key => $row) {
            $data['cabang'][] = $row->cabang_name;
            $data['total_trucks'][] = $row->count;
        }

        $debit = DB::select("SELECT SUM(a.nominal) AS total_income FROM ".$schema.".coa_activity AS a JOIN ".$schema.".coa_master_sheet AS b ON a.coa_id = b.id WHERE report_active = 'True' AND b.jurnal_category = 'DEBIT' ");
        $credit = DB::select("SELECT SUM(a.nominal) AS total_income FROM ".$schema.".coa_activity AS a JOIN ".$schema.".coa_master_sheet AS b ON a.coa_id = b.id WHERE report_active = 'True' AND b.jurnal_category = 'CREDIT' ");
        $totalIncome = $debit[0]->total_income - $credit[0]->total_income;
        
        
        $data['driver'] = DB::select("SELECT a.driver_name, a.driver_status, COUNT(c.id) AS total_rit FROM ".$schema.".ex_master_driver AS a JOIN users AS b ON a.user_id = b.id LEFT JOIN ".$schema.".expedition_activity AS c ON a.id = c.driver_id GROUP BY c.driver_id, a.driver_name, a.driver_status ORDER BY total_rit DESC LIMIT 5");
        $data['truckRit'] = DB::select("SELECT a.truck_name, a.truck_plat, a.truck_status, COUNT(c.id) AS total_rit FROM ".$schema.".ex_master_truck AS a LEFT JOIN ".$schema.".expedition_activity AS c ON a.id = c.truck_id GROUP BY c.truck_id, a.truck_name, a.truck_status, a.truck_plat ORDER BY total_rit DESC LIMIT 5");
        $data['total_expedisi'] = $totalEx[0];
        $data['total_on_progress'] = $totalOnProggres[0];
        $data['total_close'] = $totalClose[0];
        $data['total_repair'] = $totalrepair[0];
        $data['total_repairBan'] = $totalrepairBan[0];
        $data['total_repairNonBan'] = $totalrepairNonBan[0];
        $data['total_truck'] = $totaltruck[0];
        $data['total_income'] = number_format($totalIncome,0,',','.');
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
