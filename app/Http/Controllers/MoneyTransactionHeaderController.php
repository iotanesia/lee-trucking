<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\MoneyTransactionHeader;
use App\Models\GlobalParam;
use App\Models\Cabang;
use App\Models\Rekening;
use Auth;

class MoneyTransactionHeaderController extends Controller
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
        $data['title'] = 'Pinjaman Karyawan';
        $data['user'] = User::where('group_id', '<>', 8)->get();
        $data['no_rek'] = Rekening::where('is_deleted', 'f')->get();
        $data['status'] = GlobalParam::where('param_type', 'TRUCK_STATUS')->get();
        $data['cabangList'] = Cabang::all();
        return view('kasbon.pinjaman-karyawan.index', $data);
    }
    
    public function indexModal(Request $request)
    {
        $data['title'] = 'Penanaman Modal';
        $data['user'] = User::where('group_id', 8)->get();
        $data['no_rek'] = Rekening::where('is_deleted', 'f')->get();
        $data['status'] = GlobalParam::where('param_type', 'TRUCK_STATUS')->get();
        $data['cabangList'] = Cabang::all();
        return view('penanaman-modal.index', $data);
    }

}
