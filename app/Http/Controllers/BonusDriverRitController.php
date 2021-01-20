<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\Cabang;
use Auth;

class BonusDriverRitController extends Controller
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
    public function indexRit(Request $request)
    {
        $data['title'] = 'Bonus Driver / Rit';
        return view('bonus-driver-rit.index', $data);
    }

    public function indexReward(Request $request)
    {
        $data['title'] = 'Bonus Driver / Reward';
        return view('bonus-driver-reward.index', $data);
    }
}
