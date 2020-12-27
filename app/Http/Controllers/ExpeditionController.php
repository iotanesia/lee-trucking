<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\ExpeditionActivity;
use Auth;

class ExpeditionController extends Controller
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
        $data['title'] = 'Expedition';
        $data['status'] = GlobalParam::where('param_type', 'STATUS_ACTIVITY')->get();
        $data['group'] = ExpeditionActivity::where('is_deleted', 'f')->get();
        return view('expedition.new_ex.index', $data);
    }
}
