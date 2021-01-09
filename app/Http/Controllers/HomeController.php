<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\UserDetail;
use Auth;

class HomeController extends Controller
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
        return view('home');
    }

    public function indexTenan(Request $request)
    {
        return view('tenan');
    }

    public function indexTrx(Request $request)
    {
        $customerList = Customer::all();
        $tenanList = Tenan::all();
        return view('transaksi', compact('customerList', 'tenanList'));
    }

    public function myProfile(Request $request)
    {
        $data['user_detail'] = UserDetail::where('id_user', Auth::user()->id)->first();
        return view('my_profile', $data);
    }

}
