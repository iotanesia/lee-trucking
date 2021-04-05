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

        return $cabang;
    }
}
