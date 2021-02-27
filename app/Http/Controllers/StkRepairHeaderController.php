<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\StkRepairHeader;
use App\Models\GlobalParam;
use App\Models\SparePart;
use App\Models\Cabang;
use App\Models\Truck;
use Auth;

class StkRepairHeaderController extends Controller
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
        // $tgl = '2020-01-07 19:00:00';
        // dd(date('Y-m-d H:i:s', strtotime($tgl.' +1 hour')));
        $data['title'] = 'Repair Truck';
        $data['sparepart'] = SparePart::where('group_sparepart_id', '<>', 5)->get();
        $data['truck'] = Truck::all();
        return view('repair-truck.index', $data);
    }
}
