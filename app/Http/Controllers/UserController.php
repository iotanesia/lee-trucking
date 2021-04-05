<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Group;
use App\Models\Cabang;
use App\Models\GlobalParam;
use Auth;

class UserController extends Controller
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
        $data['title'] = 'User';
        $data['group'] = Group::get();
        $data['cabang'] = Cabang::where('is_deleted', 'f')->get();
        $data['jk'] = GlobalParam::where('param_type', 'JENIS_KELAMIN')->get();
        $data['agama'] = GlobalParam::where('param_type', 'AGAMA')->get();

        return view('settings.user.index', $data);
    }
}
