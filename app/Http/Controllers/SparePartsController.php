<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\StkGroupSparepart;
use App\Models\StkHistorySparePart;
use App\Models\SparePart;
use App\Models\Kenek;
use App\Models\Rekening;
use Auth;
use DNS1D;

class SparePartsController extends Controller
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
        $data['title'] = 'Spare Parts';
        $data['status'] = GlobalParam::where('param_type', 'SPAREPART_STATUS')->get();
        $data['satuan'] = GlobalParam::where('param_type', 'SATUAN')->get();
        $data['type'] = GlobalParam::where('param_type', 'SPAREPART_TYPE')->get();
        $data['jenis'] = GlobalParam::where('param_type', 'SPAREPART_JENIS')->get();
        $data['group'] = StkGroupSparepart::where('is_deleted', 'f')->get();
        $data['no_rek'] = Rekening::where('is_deleted', 'f')->get();
        return view('master.spareparts.index', $data);
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

    public function detail($id) {
        $data['title'] = 'Detail Sparepart';
        $data['status'] = GlobalParam::where('param_type', 'SPAREPART_STATUS')->get();
        $data['satuan'] = GlobalParam::where('param_type', 'SATUAN')->get();
        $data['type'] = GlobalParam::where('param_type', 'SPAREPART_TYPE')->get();
        $data['jenis'] = GlobalParam::where('param_type', 'SPAREPART_JENIS')->get();
        $data['group'] = StkGroupSparepart::where('is_deleted', 'f')->get();
        $data['sparePart'] = SparePart::find($id);
        $data['stkHistorySparePart'] = StkHistorySparePart::where('sparepart_id', $id)->get();
        $data['no_rek'] = Rekening::where('is_deleted', 'f')->get();
        return view('master.spareparts.detail', $data);
    }

    public function getBarcode($code) {
        $data['code'] = $code;
        return view('master.spareparts.barcode', $data);
    }
}
