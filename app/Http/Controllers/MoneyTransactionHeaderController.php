<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\MoneyTransactionHeader;
use App\Models\GlobalParam;
use App\Models\Cabang;
use App\Models\Rekening;
use Auth;

class MoneyTransactionHeaderController extends Controller
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
        $data['title'] = 'Pinjaman Karyawan';
        $data['user'] = User::where('group_id', '<>', 8)->get();
        $data['no_rek'] = Rekening::where('is_deleted', 'f')->get();
        $data['status'] = GlobalParam::where('param_type', 'TRUCK_STATUS')->get();
        $data['cabangList'] = Cabang::all();
        return view('kasbon.pinjaman-karyawan.index', $data);
    }

    public function indexOutCome(Request $request)
    {
        $data['title'] = 'Uang Keluar';
        $data['user'] = User::where('group_id', '<>', 8)->get();
        $data['no_rek'] = Rekening::where('is_deleted', 'f')->get();
        $data['status'] = GlobalParam::where('param_type', 'TRUCK_STATUS')->get();
        $data['cabangList'] = Cabang::all();
        return view('kasbon.uang-keluar.index', $data);
    }
    
    public function indexModal(Request $request)
    {
        $data['title'] = 'Penanaman Modal';
        $data['user'] = User::where('group_id', 8)->get();
        $data['no_rek'] = Rekening::where('is_deleted', 'f')->get();
        $data['status'] = GlobalParam::where('param_type', 'TRUCK_STATUS')->get();
        $data['cabangList'] = Cabang::all();
        return view('penanaman-modal.index', $data);
    }

    public function detail($id) {
        $data['user'] = User::where('group_id', '<>', 8)->get();
        $data['no_rek'] = Rekening::where('is_deleted', 'f')->get();
        $data['status'] = GlobalParam::where('param_type', 'TRUCK_STATUS')->get();
        $data['title'] = 'Pinjaman Karyawan';
        $data['pinjaman'] = MoneyTransactionHeader::join('public.users', 'users.id', 'money_transaction_header.user_id')
                            ->with(['money_detail_termin' => function($query){ 
                                $query->leftJoin('coa_master_rekening', 'coa_master_rekening.id', 'money_detail_termin.rek_id')->select('money_detail_termin.*', 'coa_master_rekening.rek_name', 'coa_master_rekening.rek_no',  'coa_master_rekening.id');
                            }])
                            ->leftjoin('coa_master_rekening', 'coa_master_rekening.id', 'money_transaction_header.rek_id')
                            ->select('money_transaction_header.*', 'money_transaction_header.status', 'users.name as name_user', 'coa_master_rekening.rek_no', 'coa_master_rekening.rek_name')
                            ->where('category_name', 'PINJAMAN_KARYAWAN')
                            ->where('user_id', $id)
                            ->get();

        foreach($data['pinjaman'] as $row) {
            $row->data_json = $row->toJson();
        }

        return view('kasbon.pinjaman-karyawan.detail', $data);
    }



}
