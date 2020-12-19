<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Ojk;
use App\Models\GlobalParam;
use App\Models\Driver;
use App\Models\Kenek;
use App\Models\Provinsi;
use App\Models\Kecamatan;
use App\Models\Kabupaten;
use App\Models\Cabang;
use Auth;

class OjkController extends Controller
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
        $data['title'] = 'Ojk';
        $data['status'] = GlobalParam::where('param_type', 'OJK_STATUS')->where('status_active', 1)->get();
        $data['provinsiList'] = Provinsi::orderBy('provinsi', 'ASC')->get();
        $data['kabupatenList'] = Kabupaten::orderBy('kabupaten', 'ASC')->get();
        $data['kecamatanList'] = Kecamatan::orderBy('kecamatan', 'ASC')->get();
        $data['cabangList'] = Cabang::get();
        $data['kenekList'] = Kenek::get();

        return view('master.ojk.index', $data);
    }
}
