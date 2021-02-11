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
        $data['title'] = 'Repair Truck';
        $data['sparepart'] = Sparepart::all();
        $data['truck'] = Truck::all();
        return view('repair-truck.index', $data);
    }
}
