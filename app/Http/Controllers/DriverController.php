<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\Driver;
use App\Models\Kenek;
use App\Models\Group;
use App\Models\Cabang;
use Auth;

class DriverController extends Controller
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
        $data['title'] = 'Driver';
        $data['status'] = GlobalParam::where('param_type', 'DRIVER_STATUS')->where('status_active', 1)->get();
        $data['kenekList'] = Kenek::get();
        $groupCheck = Group::where('group_name', 'Driver')->first();
        $data['users'] = User::where('group_id', $groupCheck->id)->get();
        $data['cabangList'] = Cabang::where('is_deleted', 'f')->get();
        return view('master.driver.index', $data);
    }
}
