<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\StkGroupSparepart;
use App\Models\Kenek;
use App\Models\Rekening;
use Auth;

class GudangController extends Controller
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
        $data['title'] = 'Gudang';
        $data['status'] = GlobalParam::where('param_type', 'SPAREPART_STATUS')->get();
        $data['satuan'] = GlobalParam::where('param_type', 'SATUAN')->get();
        $data['type'] = GlobalParam::where('param_type', 'SPAREPART_TYPE')->get();
        $data['jenis'] = GlobalParam::where('param_type', 'SPAREPART_JENIS')->get();
        $data['group'] = StkGroupSparepart::where('is_deleted', 'f')->get();
        $data['no_rek'] = Rekening::where('is_deleted', 'f')->get();
        return view('master.gudang.index', $data);
    }

    public function indexpurchase(Request $request)
    {
        $data['title'] = 'Purchased Spare Parts';
        $data['status'] = GlobalParam::where('param_type', 'SPAREPART_STATUS')->get();
        $data['satuan'] = GlobalParam::where('param_type', 'SATUAN')->get();
        $data['type'] = GlobalParam::where('param_type', 'SPAREPART_TYPE')->get();
        $data['jenis'] = GlobalParam::where('param_type', 'SPAREPART_JENIS')->get();
        $data['group'] = StkGroupSparepart::where('is_deleted', 'f')->get();
        $data['no_rek'] = Rekening::where('is_deleted', 'f')->get();
        return view('master.purchased-spareparts.index', $data);
    }

    public function indexUnpaid(Request $request)
    {
        $data['title'] = 'Hutang Stok';
        $data['status'] = GlobalParam::where('param_type', 'SPAREPART_STATUS')->get();
        $data['satuan'] = GlobalParam::where('param_type', 'SATUAN')->get();
        $data['type'] = GlobalParam::where('param_type', 'SPAREPART_TYPE')->get();
        $data['jenis'] = GlobalParam::where('param_type', 'SPAREPART_JENIS')->get();
        $data['group'] = StkGroupSparepart::where('is_deleted', 'f')->get();
        $data['no_rek'] = Rekening::where('is_deleted', 'f')->get();
        return view('kasbon.hutang-stok.index', $data);
    }
}
