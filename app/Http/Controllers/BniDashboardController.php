<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BniDashBoadrd;
use App\Exports\ExportBniDashboard;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class BniDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataSl = BniDashBoadrd::getSlChart();
        foreach($dataSl as $key => $val) {
            $data['sl_label'][] = $val['unit'];
            $data['sl_count'][] = $val['count'];
        }
        
        $dataProduk = BniDashBoadrd::getSlProdukChart();
        foreach($dataProduk as $key => $val) {
            $data['produk_label'][] = $val['produk'];
            $data['produk_count'][] = $val['count'];
        }

        $data['filterUnit'] = BniDashBoadrd::mstUnit();
        $data['filterKol'] = BniDashBoadrd::mstKol();
        $data['filterFlagCovid'] = BniDashBoadrd::mstflagCovid();
        $data['filterFlag'] = BniDashBoadrd::mstflag();
        $data['filterProduk'] = BniDashBoadrd::mstProduk();
       
        return view('bni.index', $data);
    }

    public function indexAll(Request $request)
    {
        $dataSl = BniDashBoadrd::getSlAllChart();
        foreach($dataSl as $key => $val) {
            $data['sl_label'][] = $val['unit'];
            $data['sl_count'][] = $val['count'];
        }

        return view('bni.index-detail', $data);
    }

    public function excelExportBni(Request $request){
        $date = $request->dateRangeBO;
        $filterSelectKol = (isset($request->filterSelectKol) ? $request->filterSelectKol : 'Kol');
        $filterSelectFlag = (isset($request->filterSelectFlag) ? $request->filterSelectFlag : 'Flag');
        $filterSelectFlagCovid = (isset($request->filterSelectFlagCovid) ? $request->filterSelectFlagCovid : 'Flag Covid');
        $filterSelectUnit = (isset($request->filterSelectUnit) ? $request->filterSelectUnit : 'Unit');
        $filterSelectProduk = (isset($request->filterSelectProduk) ? $request->filterSelectProduk : 'Produk');
        $dates = explode('-',$date);
        $ids = null;
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');

        $namaFile = 'Laporan Data SL '.Carbon::parse($startDate)->formatLocalized('%d %B %Y').'-'.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportBniDashboard($startDate, $endDate, $filterSelectKol, $filterSelectFlag, $filterSelectFlagCovid, $filterSelectUnit, $filterSelectProduk), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }

    }

    public function tnc() {
        return view('bni.tnc');
    }
}
