<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\Kenek;
use App\Models\Cabang;
use Auth;

class KenekController extends Controller
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
        $data['title'] = 'Kenek';
        $data['status'] = GlobalParam::where('param_type', 'KENEK_STATUS')->get();
        $data['cabangList'] = Cabang::where('is_deleted', 'f')->get();
        return view('master.kenek.index', $data);
    }
}
