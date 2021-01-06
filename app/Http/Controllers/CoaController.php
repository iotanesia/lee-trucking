<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\Coa;
use Auth;

class CoaController extends Controller
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
        $data['title'] = 'COA';
        $data['status'] = GlobalParam::where('param_type', 'COA_STATUS')->get();
        $data['category'] = GlobalParam::where('param_type', 'COA_CATEGORY')->get();
        $data['parent'] = Coa::get();
        return view('master.coa.index', $data);
    }
}
