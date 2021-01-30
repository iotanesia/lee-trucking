<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\StkRepairHeader;
use App\Models\GlobalParam;
use App\Models\Cabang;
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
        return view('repair-truck.index', $data);
    }
}
