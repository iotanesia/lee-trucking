<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Cabang;
use App\Models\Driver;
use App\Models\ExpeditionActivity;
use App\Models\GlobalParam;
use App\Models\Kenek;
use App\Models\Rekening;
use App\Models\Truck;
use App\Models\ExStatusActivity;
use Auth;

class ExpeditionController extends Controller
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
        $data['title'] = 'Expedition';
        $data['status'] = GlobalParam::where('param_type', 'STATUS_ACTIVITY')->get();
        $data['sj_type'] = GlobalParam::where('param_type', 'SJ_TYPE')->get();
        $data['payment_method'] = GlobalParam::where('param_type', 'PAYMENT_METHOD')->get();
        $data['group'] = ExpeditionActivity::where('is_deleted', 'f')->get();
        $data['driver'] = Driver::where('is_deleted', 'f')->get();
        $data['truck'] = Truck::where('is_deleted', 'f')->get();
        $data['kenek'] = Kenek::where('is_deleted', 'f')->get();

        return view('expedition.new_ex.index', $data);
    }

    public function indexTracking(Request $request)
    {
        $data['title'] = 'Expedition Tracking';
        $data['status'] = GlobalParam::where('param_type', 'STATUS_ACTIVITY')->get();
        $data['sj_type'] = GlobalParam::where('param_type', 'SJ_TYPE')->get();
        $data['payment_method'] = GlobalParam::where('param_type', 'PAYMENT_METHOD')->get();
        $data['group'] = ExpeditionActivity::where('is_deleted', 'f')->get();
        $data['driver'] = Driver::where('is_deleted', 'f')->get();
        $data['truck'] = Truck::where('is_deleted', 'f')->get();
        $data['kenek'] = Kenek::where('is_deleted', 'f')->get();

        return view('expedition.tracking.index', $data);
    }

    public function indexApprove(Request $request)
    {
        $data['title'] = 'Expedition Approval';
        $data['status'] = GlobalParam::where('param_type', 'STATUS_ACTIVITY')->get();
        $data['sj_type'] = GlobalParam::where('param_type', 'SJ_TYPE')->get();
        $data['payment_method'] = GlobalParam::where('param_type', 'PAYMENT_METHOD')->get();
        $data['group'] = ExpeditionActivity::where('is_deleted', 'f')->get();
        $data['driver'] = Driver::where('is_deleted', 'f')->get();
        $data['truck'] = Truck::where('is_deleted', 'f')->get();
        $data['kenek'] = Kenek::where('is_deleted', 'f')->get();
        $data['no_rek'] = Rekening::where('is_deleted', 'f')->get();

        return view('expedition.approval.index', $data);
    }

    public function indexApproveOtv(Request $request)
    {
        $data['title'] = 'Expedition Approval OTV Toko';
        $data['status'] = GlobalParam::where('param_type', 'STATUS_ACTIVITY')->get();
        $data['sj_type'] = GlobalParam::where('param_type', 'SJ_TYPE')->get();
        $data['payment_method'] = GlobalParam::where('param_type', 'PAYMENT_METHOD')->get();
        $data['group'] = ExpeditionActivity::where('is_deleted', 'f')->get();
        $data['driver'] = Driver::where('is_deleted', 'f')->get();
        $data['truck'] = Truck::where('is_deleted', 'f')->get();
        $data['cabang'] = Cabang::where('is_deleted', 'f')->get();
        $data['kenek'] = Kenek::where('is_deleted', 'f')->get();

        return view('expedition.approval-otv.index', $data);
    }

    public function detailTracking($id)
    {
        $data['title'] = 'Tracking';
        $data['expedition'] = ExpeditionActivity::join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
                              ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
                              ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
                              ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
                              ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
                              ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
                              ->leftJoin('ex_master_kenek','expedition_activity.kenek_id', 'ex_master_kenek.id')
                              ->select('expedition_activity.*', 'ex_wil_kecamatan.kecamatan', 'ex_master_cabang.cabang_name', 'ex_wil_kabupaten.kabupaten', 'ex_master_driver.driver_name', 'ex_master_truck.truck_plat', 'ex_master_truck.truck_name', 'ex_master_kenek.kenek_name')
                              ->find($id);
        $data['detail'] = ExStatusActivity::where('ex_id', $id)->orderBy('id', 'ASC')->get();

        return view('expedition.tracking.detail', $data);
    }
}
