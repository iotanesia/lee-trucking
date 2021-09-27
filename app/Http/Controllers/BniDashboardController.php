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
        $data['sl'] = BniDashBoadrd::getSlChart();
        $data['slAll'] = BniDashBoadrd::getSlAllChart();
        $data['produk'] = BniDashBoadrd::getSlProdukChart();
       
        return view('bni.index', $data);
    }
}
