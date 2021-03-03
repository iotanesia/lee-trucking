<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\Driver;
use App\Models\Kenek;
use App\Models\Group;
use Auth;
use DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($schema)
    {
        $data['bulan'] = [];
        $data['total'] = [];
        $data['cabang'] = [];
        $data['total_truck'] = [];
        $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $ex = DB::select("SELECT date_part('month', updated_at) AS months, COUNT(id) FROM ".$schema.".expedition_activity GROUP BY months ORDER BY months ASC");
        $truck = DB::select("SELECT b.cabang_name, COUNT(a.id) FROM ".$schema.".ex_master_truck AS a JOIN ".$schema.".ex_master_cabang AS b ON a.cabang_id = b.id GROUP BY cabang_id, b.cabang_name");
        // dd($truck);
        foreach($ex as $key => $row) {
            $row->months = $month[($row->months - 1)];
            $data['bulan'][] = $row->months;
            $data['total'][] = $row->count;
        }

        foreach($truck as $key => $row) {
            $data['cabang'][] = $row->cabang_name;
            $data['total_truck'][] = $row->count;
        }

        // dd($data);

        // $data = json_encode($data);
        return view('dashboard.index', $data);
    }
}
