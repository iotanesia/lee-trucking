<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;
use App\Models\Group;
use App\Models\Cabang;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkRoles() {
        $user = Auth::user();
        $role = $user->group_id;

        $checkRole = Group::find($role);

        if($checkRole) {
            $roles = strpos($checkRole->group_name, "Dawuan -");

            if($roles !== TRUE) {
                $cabang = Cabang::where('cabang_name', 'LIKE', '%Cabang TSJ%')->get()->pluck('id');
                
            } else {
                $cabang = Cabang::where('cabang_name', 'LIKE', '%Cabang Dawuan%')->get()->pluck('id');

            }
        }

        return $cabang;
    }
}
