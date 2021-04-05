<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Models\ExpeditionActivity;
use App\Models\Ojk;
use App\Models\Truck;
use App\Models\Kabupaten;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App\Exports\ExportExpeditionRit;
use Carbon\Carbon;

class ExpeditionAndRitReportController extends Controller
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
        $data['title'] = 'Laporan Ekspedisi Dan Rit';
        return view('expedition-rit-report.index', $data);
    }

    public function exportExcelRitTujuan(Request $request){
        $date = $request->dateRangeRitTujuan;
        $dates = explode('-',$date);
        
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        
        $namaFile = 'Ekspedisi dan Rit Tujuan '.Carbon::parse($startDate)->formatLocalized('%d %B %Y').' - '.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportExpeditionRit($startDate, $endDate, 'Tujuan'), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }
        
    }

    public function exportExcelRitDriver(Request $request){
        $date = $request->dateRangeRitDriver;
        $dates = explode('-',$date);
        
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        
        $namaFile = 'Ekspedisi dan Rit Driver '.Carbon::parse($startDate)->formatLocalized('%d %B %Y').' - '.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportExpeditionRit($startDate, $endDate, 'Driver'), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }
        
    }
    
    public function exportExcelRitTruck(Request $request){
        $date = $request->dateRangeRitTruck;
        $dates = explode('-',$date);
        
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        
        $namaFile = 'Ekspedisi dan Rit Truck '.Carbon::parse($startDate)->formatLocalized('%d %B %Y').' - '.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportExpeditionRit($startDate, $endDate, 'Truck'), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }
        
    }
}
