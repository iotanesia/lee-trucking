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
}
