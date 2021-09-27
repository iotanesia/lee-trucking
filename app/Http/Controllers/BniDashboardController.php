<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BniDashBoadrd;

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
       
        return view('bni.index', $data);
    }
}
