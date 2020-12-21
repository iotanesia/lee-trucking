<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\StkGroupSparepart;
use App\Models\StkHistorySparePart;
use App\Models\GlobalParam;
use Auth;

class StkGroupSparePartController extends Controller
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
        $data['title'] = 'Spare Parts Group';
        $data['status'] = GlobalParam::where('param_type', 'SPAREPART_GROUP_STATUS')->get();
        return view('master.spareparts_group.index', $data);
    }
}
