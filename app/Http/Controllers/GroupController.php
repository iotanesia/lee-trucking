<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Group;
use App\Models\GlobalParam;
use App\Models\Cabang;
use Auth;

class GroupController extends Controller
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
        $data['title'] = 'Group';
        $data['status'] = GlobalParam::where('param_type', 'GROUP_STATUS')->get();
        return view('settings.role.index', $data);
    }

}
