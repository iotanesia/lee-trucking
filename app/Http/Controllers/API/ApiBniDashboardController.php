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
        $filterKol = (isset($data['filter_select_kol'])) ? $data['filter_select_kol'] : 'Kol';
        $filterFlag = (isset($data['filter_select_flag'])) ? $data['filter_select_flag'] : 'Flag';
        $filterFlagCovid = (isset($data['filter_select_flagCovid'])) ? $data['filter_select_flagCovid'] : 'Flag Covid';
        $filterUnit = (isset($data['filter_select_unit'])) ? $data['filter_select_unit'] : 'Unit';
        $filterProduk = (isset($data['filter_select_produk'])) ? $data['filter_select_produk'] : 'Produk';
        $endDate = $datas['end_date'];
        $endDate = $datas['end_date'];
        $data = BniDashBoadrd::
          whereBetween('dates', [$startDate, $endDate])->
          where(function($query) use($filterKol) {
            if($filterKol) {
              if($filterKol != 'Kol'){
                $query->where('kol', $filterKol);
              }
            }
          })->
          where(function($query) use($filterFlag) {
            if($filterFlag) {
              if($filterFlag != 'Flag'){
                $query->where('flag', $filterFlag);
              }
            }
          })->
          where(function($query) use($filterFlagCovid) {
            if($filterFlagCovid) {
              if($filterFlagCovid != 'Flag Covid'){
                $query->where('flag_covid', $filterFlagCovid);
              }
            }
          })->
          where(function($query) use($filterUnit) {
            if($filterUnit) {
              if($filterUnit != 'Unit'){
                $query->where('unit', $filterUnit);
              }
            }
          })->
          where(function($query) use($filterProduk) {
            if($filterProduk) {
              if($filterProduk != 'Produk'){
                $query->where('produk', $filterProduk);
              }
            }
          })->
          take(50)->orderBy('dates','DESC')->
          get();
          // dd($data);      
          return datatables($data)->toJson();
      }
    }

}
