<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Truck;
use App\Models\GlobalParam;
use App\Models\Driver;
use App\Models\Kenek;
use App\Models\Group;
use App\Models\Cabang;
use Auth;
use DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($schema, $id_user = null)
    {
        if($id_user) {
            $user = User::find($id_user);
            $role = $user->cabang_id;
            $cabang = null;
            
            $checkRole = Cabang::find($role);
            
            if($checkRole) {
                $roles = strpos($checkRole->cabang_name, " Dawuan ");
    
                if($roles !== false) {
                    $cabang = Cabang::where('cabang_name', 'LIKE', '%Cabang Dawuan%')->get()->pluck('id');
    
                } else {
                    $cabang = Cabang::where('cabang_name', 'LIKE', '%Cabang TSJ%')->get()->pluck('id');
                }
            }
    
            if($cabang) {
                $ids = json_decode($cabang, true);
                $idRole = implode(', ', $ids);
                $queryRole = 'AND b.cabang_id IN ('.$idRole.')';
            }

        } else {
            $queryRole = '';
        }

        $data['bulan'] = [];
        $data['total'] = [];
        $data['cabang'] = [];
        $data['total_truck'] = [];
        $bln = date('m');
        $thn = date('Y');
        $month = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $ex = DB::select("SELECT date_part('month', a.tgl_po) AS months, date_part('year', a.tgl_po) AS years, COUNT(a.id) FROM ".$schema.".expedition_activity as a
              JOIN users as b ON b.id = a.user_id  AND a.is_deleted = 'f' ".$queryRole."   
              GROUP BY months,years ORDER BY months,years ASC");
        $truck = DB::select("SELECT a.cabang_name, COUNT(b.id) FROM ".$schema.".ex_master_truck AS b JOIN ".$schema.".ex_master_cabang AS a ON b.cabang_id = a.id 
                 WHERE b.is_deleted = false ".$queryRole." GROUP BY cabang_id, a.cabang_name");
        // dd($truck);
        foreach($ex as $key => $row) {
            $row->months = $month[($row->months - 1)];
            $data['bulan'][] = $row->months;
            $data['total'][] = $row->count;
        }

        foreach($truck as $key => $row) {
            $data['cabang'][] = $row->cabang_name;
            $data['total_truck'][] = $row->count;
        }

        // dd($data);

        // $data = json_encode($data);
        return view('dashboard.index', $data);
    }

    public function indexTandC() {
        return view('dashboard.indexTnC');
    }
}
