<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\StkRepairHeader;
use App\Models\GlobalParam;
use App\Models\Sparepart;
use App\Models\Cabang;
use App\Models\Truck;
use Auth;

class StkRepairBanHeaderController extends Controller
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
        $data['title'] = 'Repair Ban Truck';
        $data['sparepart'] = Sparepart::where('group_sparepart_id', 5)->get();
        $data['truck'] = Truck::all();
        return view('repair-ban-truck.index', $data);
    }
}
