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
use App\Exports\ExportInvoiceBO;
use App\Exports\ExportInvoiceBA;
use App\Exports\ExportInvoiceBJ;
use App\Exports\ExportInvoiceBF;
use App\Exports\ExportInvoiceDO;
use App\Exports\ExportInvoiceDA;
use App\Exports\ExportInvoiceDJ;
use App\Exports\ExportInvoiceDF;
use Carbon\Carbon;

class InvoiceController extends Controller
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
        $user = Auth::User();
        // dd($user);
        $data['title'] = 'Laporan Invoice';
        $data['user'] = $user;

                                                    // dd($data);
        
        return view('invoice.index', $data);
    }

    public function dataTableInvoiceReport(){
        $data = ExpeditionActivity::leftJoin('ex_master_ojk' ,'expedition_activity.id_ojk','ex_master_ojk.id')
        ->leftJoin('ex_wil_kabupaten','ex_master_ojk.kabupaten_id','ex_wil_kabupaten.id')
        ->leftJoin('ex_master_truck','expedition_activity.truck_id','ex_master_truck.id')
        ->select(DB::raw('count(*) as rit'),'expedition_activity.tgl_po','ex_wil_kabupaten.kabupaten'
                ,'ex_master_truck.truck_plat','expedition_activity.jumlah_palet'
                ,'expedition_activity.toko','expedition_activity.harga_otv')
          ->groupBy('expedition_activity.ojk_id', 'ex_master_truck.truck_plat')->paginate();

       return json_decode($data);
    }

    public function exportExcelBO(Request $request){
        $date = $request->dateRangeBO;
        $dates = explode('-',$date);

        $cekRole = $this->checkRoles();
        $ids = null;

        if($cekRole) {
            $ids = json_decode($cekRole, true);
        }
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));
        $noInvoice = $request->noInvoiceBO;
        $jenisPembayaran = $request->filterPembayaranBO;

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');

        $namaFile = 'Invoice BO '.Carbon::parse($startDate)->formatLocalized('%d %B %Y').'-'.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportInvoiceBO($startDate, $endDate, $noInvoice, $jenisPembayaran, $ids), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }

    }

    public function exportExcelBA(Request $request){
        $date = $request->dateRangeBA;
        $dates = explode('-',$date);

        $cekRole = $this->checkRoles();
        $ids = null;

        if($cekRole) {
            $ids = json_decode($cekRole, true);
        }
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));
        $noInvoice = $request->noInvoiceBA;
        $jenisPembayaran = $request->filterPembayaranBA;

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');

        $namaFile = 'Invoice BA '.Carbon::parse($startDate)->formatLocalized('%d %B %Y').'-'.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportInvoiceBA($startDate, $endDate, $noInvoice, $jenisPembayaran, $ids), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }

    }

    public function exportExcelBJ(Request $request){
        $date = $request->dateRangeBJ;
        $dates = explode('-',$date);

        $cekRole = $this->checkRoles();
        $ids = null;

        if($cekRole) {
            $ids = json_decode($cekRole, true);
        }
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));
        $noInvoice = $request->noInvoiceBJ;
        $jenisPembayaran = $request->filterPembayaranBJ;

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');

        $namaFile = 'Invoice BJ'.Carbon::parse($startDate)->formatLocalized('%d %B %Y').'-'.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportInvoiceBJ($startDate, $endDate, $noInvoice, $jenisPembayaran, $ids), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }

    }

    public function exportExcelBF(Request $request){
        $date = $request->dateRangeBF;
        $dates = explode('-',$date);

        $cekRole = $this->checkRoles();
        $ids = null;

        if($cekRole) {
            $ids = json_decode($cekRole, true);
        }
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));
        $noInvoice = $request->noInvoiceBF;
        $jenisPembayaran = $request->filterPembayaranBF;

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');

        $namaFile = 'Invoice BF '.Carbon::parse($startDate)->formatLocalized('%d %B %Y').'-'.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportInvoiceBF($startDate, $endDate, $noInvoice, $jenisPembayaran, $ids), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }

    }

    public function exportExcelDO(Request $request){
        $date = $request->dateRangeDO;
        $dates = explode('-',$date);

        $cekRole = $this->checkRoles();
        $ids = null;

        if($cekRole) {
            $ids = json_decode($cekRole, true);
        }
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));
        $noInvoice = $request->noInvoiceDO;
        $jenisPembayaran = $request->filterPembayaranDO;

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');

        $namaFile = 'Invoice DO '.Carbon::parse($startDate)->formatLocalized('%d %B %Y').'-'.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportInvoiceDO($startDate, $endDate, $noInvoice, $jenisPembayaran, $ids), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }

    }

    public function exportExcelDA(Request $request){
        $date = $request->dateRangeDA;
        $dates = explode('-',$date);

        $cekRole = $this->checkRoles();
        $ids = null;

        if($cekRole) {
            $ids = json_decode($cekRole, true);
        }
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));
        $noInvoice = $request->noInvoiceDA;
        $jenisPembayaran = $request->filterPembayaranDA;

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');

        $namaFile = 'Invoice DA '.Carbon::parse($startDate)->formatLocalized('%d %B %Y').'-'.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportInvoiceDA($startDate, $endDate, $noInvoice, $jenisPembayaran, $ids), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }

    }

    public function exportExcelDJ(Request $request){
        $date = $request->dateRangeDJ;
        $dates = explode('-',$date);

        $cekRole = $this->checkRoles();
        $ids = null;

        if($cekRole) {
            $ids = json_decode($cekRole, true);
        }
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));
        $noInvoice = $request->noInvoiceDJ;
        $jenisPembayaran = $request->filterPembayaranDJ;

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');

        $namaFile = 'Invoice DJ'.Carbon::parse($startDate)->formatLocalized('%d %B %Y').'-'.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportInvoiceDJ($startDate, $endDate, $noInvoice, $jenisPembayaran, $ids), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }

    }

    public function exportExcelDF(Request $request){
        $date = $request->dateRangeDF;
        $dates = explode('-',$date);

        $cekRole = $this->checkRoles();
        $ids = null;

        if($cekRole) {
            $ids = json_decode($cekRole, true);
        }
        $startDate = Date('Y-m-d',strtotime($dates[0]));
        $endDate =  Date('Y-m-d',strtotime($dates[1]));
        $noInvoice = $request->noInvoiceDF;
        $jenisPembayaran = $request->filterPembayaranDF;

        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');

        $namaFile = 'Invoice DF '.Carbon::parse($startDate)->formatLocalized('%d %B %Y').'-'.Carbon::parse($endDate)->formatLocalized('%d %B %Y');
        // if($request->tipeFile == "excel"){
        return Excel::download(new ExportInvoiceDF($startDate, $endDate, $noInvoice, $jenisPembayaran, $ids), $namaFile.'.xlsx');
        // }else if($request->tipeFile == "pdf"){
        //     return Excel::download(new ExportInvoiceBO($startDate, $endDate), $namaFile.'.pdf', Excel::TCPDF);
        // }

    }
}
