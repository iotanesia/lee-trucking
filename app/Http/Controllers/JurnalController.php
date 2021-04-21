<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\CoaActivity;
use App\Exports\ExportJurnal;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
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
        $data['sheetName'] = CoaMasterSheet::where('report_active','True')->select('sheet_name')->get();
                                                    // dd($data);

        return view('jurnal.index', $data);
    }

    public function exportJurnal(Request $request){
        $date = $request->dateRangeJurnal;
        $dates = explode('-',$date);

        $cekRole = $this->checkRoles();
        $ids = null;

        if($cekRole) {
            $ids = json_decode($cekRole, true);
        }
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));
        $filterSelect = $request->filterSelectJurnal;
        $filterAktiviti = $request->filterActivityJurnal;
        $balance = (isset($request->balanceJurnal)) ? str_replace(".", "",$request->balanceJurnal) : '';
        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');

        $namaFile = 'Laporan Jurnal '.Carbon::parse($startDate)->formatLocalized('%d %B %Y').'-'.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportJurnal($startDate, $endDate, $filterSelect, $filterAktiviti, $balance, $ids), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }

    }

}
