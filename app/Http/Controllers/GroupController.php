<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Group;
use App\Models\GlobalParam;
use App\Models\Cabang;
use Auth;
use DB;

class GroupController extends Controller
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
        $data['title'] = 'Group';
        $data['status'] = GlobalParam::where('param_type', 'GROUP_STATUS')->get();
        return view('settings.role.index', $data);
    }

    public function detail($id)
    {
        $role = 0;  
        $schema = Auth::user()->schema.'.';
        $roles = DB::table($schema.'usr_group_menu')->where('group_id', $id)->get()->pluck('menu_id')->toArray();
        $data['group_name'] = DB::table($schema.'usr_group')->find($id)->group_name;
        $data['title'] = 'Group-'.$data['group_name'];
        $data['group_id'] = $id;

        if($roles) {
            $role = implode(",", $roles);
        }
        $data['menus'] = DB::table($schema.'usr_menu')
                         ->select('usr_menu.*', DB::raw("CASE WHEN id in (".$role.") THEN 'Checked' ELSE ''  END AS checked"))
                         ->orderBy('sort', 'ASC')
                         ->get();

        return view('settings.role.detail', $data);
    }

    public function updateRole(Request $req) {
        $data = $req->all();
        $schema = Auth::user()->schema.'.';
        $delete = DB::table($schema.'usr_group_menu')->where('group_id', $data['group_id'])->delete();

        foreach($data['menus'] as $key => $row) {
            $dataInsert = [
                                'group_id' => $data['group_id'],
                                'menu_id' => $row
                            ];
            $insert = DB::table($schema.'usr_group_menu')->insert($dataInsert);
        }
        return redirect('/role');
    }

}
