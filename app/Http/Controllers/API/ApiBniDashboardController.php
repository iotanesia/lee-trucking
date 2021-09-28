<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\BniDashBoadrd;

class ApiBniDashboardController extends Controller
{
    //Invoice Report
    public function getTableBniDashboard(Request $request){
      if($request->isMethod('GET')) {
        $datas = $request->all();
        $startDate = $datas['start_date'];
        $endDate = $datas['end_date'];
        $data = BniDashBoadrd::take(10)->
          // whereBetween('dates', [$startDate, $endDate])
        orderBy('dates','DESC')
        ->get();
          // dd($data);      
          return datatables($data)->toJson();
      }
    }

}
