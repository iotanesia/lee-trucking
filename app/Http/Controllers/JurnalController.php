<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\CoaActivity;
use App\Models\CoaMasterSheet;
use Auth;

class JurnalController extends Controller
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
        $data['title'] = 'Laporan Jurnal';
      
                                                    // dd($data);

        return view('jurnal.index', $data);
    }

    public function dataTableJurnalReport(){
        $data = CoaActivity::leftJoin('coa_master_sheet' ,'coa_activity.coa_id','coa_master_sheet.id')
        ->where('coa_master_sheet.report_active','True')
        ->leftJoin('public.users','coa_activity.created_by','public.users.id')
        ->leftJoin('coa_master_rekening','coa_activity.rek_id','coa_master_rekening.id')
        ->select('coa_activity.created_at','coa_master_sheet.sheet_name'
                ,'coa_master_sheet.jurnal_category','public.users.name'
                ,'coa_master_rekening.bank_name','coa_master_rekening.rek_name'
                ,'coa_master_rekening.rek_no')->paginate();
         
       return json_decode($databuku);
    }
 
}
