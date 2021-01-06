<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\Driver;
use App\Models\GlobalParam;
use App\Models\ExpeditionActivity;
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

        return view('expedition.approval-otv.index', $data);
    }
}
