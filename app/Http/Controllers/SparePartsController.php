<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\StkGroupSparepart;
use App\Models\Kenek;
use Auth;

class SparepartsController extends Controller
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
        $data['title'] = 'Spare Parts';
        $data['status'] = GlobalParam::where('param_type', 'SPAREPART_STATUS')->get();
        $data['group'] = StkGroupSparepart::where('is_deleted', 'f')->get();
        return view('master.spareparts.index', $data);
    }
}
