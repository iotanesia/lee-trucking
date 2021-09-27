<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\ExpeditionActivity;
use App\Models\Cabang;
use Auth;
use Illuminate\Support\Facades\DB;

class BonusDriverRitController extends Controller
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
    public function indexRit(Request $request)
    {
        $data['title'] = 'Bonus Driver / Rit';
        $data['tahun'] = ExpeditionActivity::select(DB::raw("date_part('year', updated_at) AS years"))->groupBy('years')->get()->toArray();
        $data['bulan'] = ['01' => 'Januari', '02' => 'Febuari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
        return view('bonus-driver-rit.index', $data);
    }

    public function indexKenekRit(Request $request)
    {
        $data['title'] = 'Bonus Kenek / Rit';
        $data['tahun'] = ExpeditionActivity::select(DB::raw("date_part('year', updated_at) AS years"))->groupBy('years')->get()->toArray();
        $data['bulan'] = ['01' => 'Januari', '02' => 'Febuari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
        return view('bonus-driver-rit.index-kenek', $data);
    }

    public function indexReward(Request $request)
    {
        $data['title'] = 'Bonus Driver / Reward';
        return view('bonus-driver-reward.index', $data);
    }
}
