<?php

namespace App\Exports;

use App\Models\ExpeditionActivity;
use DB;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ExportExpeditionRit implements FromView, WithDrawings
{


protected $startDate;
protected $endDate;
protected $param;
protected $ids;

 function __construct($startDate, $endDate, $param, $ids) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->param = $param;
        $this->ids = $ids;
 }

/**
* @return \Illuminate\Support\Collection
*/
public function view(): View{
    setlocale(LC_TIME, 'id_ID');
    Carbon::setLocale('id');
    $startDate = $this->startDate;
    $endDate = $this->endDate;
    $param = $this->param;
    $ids = $this->ids;
    $data = null;
    $datas = null;
    if($param == 'Tujuan'){
        $data  = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
        ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
        ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
        ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
        ->join('public.users', 'public.users.id', 'expedition_activity.user_id')
       ->where(function($query) use($ids) {
            if($ids) {
               $query->whereIn('public.users.cabang_id', $ids);
            }
          })
        ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')  
        ->wherein('expedition_activity.status_activity', ['CLOSED_EXPEDITION', 'DRIVER_SELESAI_EKSPEDISI', 'DRIVER_SAMPAI_TUJUAN', 'WAITING_OWNER'])
        ->where('expedition_activity.is_deleted','false')
        ->where(function($query) use($startDate, $endDate) {
            if($startDate && $endDate){
                $query->whereBetween('expedition_activity.tgl_po', [$startDate, $endDate]);
            }
        })
        ->select(DB::raw('COUNT("ojk_id") AS total_ekspedisi'), DB::raw('SUM(ex_master_ojk.harga_ojk) AS total_ojk'), DB::raw('SUM(ex_master_ojk.harga_otv) AS total_otv'), 'expedition_activity.ojk_id','ex_wil_kabupaten.kabupaten','ex_wil_kecamatan.kecamatan')
        ->groupBy('expedition_activity.ojk_id', 'ex_wil_kabupaten.kabupaten','ex_wil_kecamatan.kecamatan')->get();
    }else if($param == 'Driver'){
        $data  = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
        ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
        ->join('public.users', 'public.users.id', 'expedition_activity.user_id')
        ->where(function($query) use($ids) {
            if($ids) {
               $query->whereIn('public.users.cabang_id', $ids);
            }
          })
        ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
        ->wherein('expedition_activity.status_activity', ['CLOSED_EXPEDITION', 'DRIVER_SELESAI_EKSPEDISI', 'DRIVER_SAMPAI_TUJUAN', 'WAITING_OWNER'])
        ->where('expedition_activity.is_deleted','false')
        ->where(function($query) use($startDate, $endDate) {
          if($startDate && $endDate){
            $query->whereBetween('expedition_activity.tgl_po', [$startDate, $endDate]);
          }
        })
        ->select(DB::raw('COUNT("driver_id") AS total_ekspedisi'), DB::raw('SUM("harga_ojk") AS total_ojk'), DB::raw('SUM("harga_otv") AS total_otv'), 'expedition_activity.driver_id', 'ex_master_driver.driver_name')
        ->groupBy('expedition_activity.driver_id', 'ex_master_driver.driver_name')->get();
    }else if($param == 'Truck'){
        $data  = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
        ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
        ->join('public.users', 'public.users.id', 'expedition_activity.user_id')
        ->where(function($query) use($ids) {
            if($ids) {
               $query->whereIn('public.users.cabang_id', $ids);
            }
          })
        ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')  
        ->wherein('expedition_activity.status_activity', ['CLOSED_EXPEDITION', 'DRIVER_SELESAI_EKSPEDISI', 'DRIVER_SAMPAI_TUJUAN', 'WAITING_OWNER'])
        ->where('expedition_activity.is_deleted','false')
        ->where(function($query) use($startDate, $endDate) {
          if($startDate && $endDate){
            $query->whereBetween('expedition_activity.tgl_po', [$startDate, $endDate]);
          }
        })
        ->select(DB::raw('COUNT("truck_id") AS total_ekspedisi'), DB::raw('SUM("harga_ojk") AS total_ojk'), DB::raw('SUM("harga_otv") AS total_otv'), 'expedition_activity.truck_id', 'ex_master_truck.truck_plat','ex_master_truck.truck_name')
        ->groupBy('expedition_activity.truck_id', 'ex_master_truck.truck_plat','ex_master_truck.truck_name')->get();
    }
    foreach($data as $row){
        $textColor = '';
        $backgroundColor = '';

        $row->param = $param;
        $row->total_ojk = 'Rp.'. number_format($row->total_ojk, 0, ',', '.');
        $row->total_otv = 'Rp.'. number_format($row->total_otv, 0, ',', '.');
        if($param == 'Tujuan'){
            $row->paramName = $row->kabupaten.', '.$row->kecamatan;
            $dataDetail = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
            ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
            ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
            ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
            ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
            ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
            ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
            ->join('public.users', 'public.users.id', 'expedition_activity.user_id')
            ->where(function($query) use($ids) {
                if($ids) {
                $query->whereIn('public.users.cabang_id', $ids);
                }
            })
            ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY') 
            ->wherein('expedition_activity.status_activity', ['CLOSED_EXPEDITION', 'DRIVER_SELESAI_EKSPEDISI', 'DRIVER_SAMPAI_TUJUAN', 'WAITING_OWNER'])
            ->where('expedition_activity.is_deleted','false')
            ->where('expedition_activity.ojk_id', $row->ojk_id)
            ->where(function($query) use($startDate, $endDate) {
              if($startDate && $endDate){
                $query->whereBetween('expedition_activity.tgl_po', [$startDate, $endDate]);
              }
            })
            ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'all_global_param.param_code as status_code',
            'ex_master_driver.driver_name', 'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name')
               ->get();
            foreach($dataDetail as $rowDetail){
              $rowDetail->payment = isset($rowDetail->otv_payment_method)?$rowDetail->otv_payment_method : '-';
              $rowDetail->tujuan = $rowDetail->kabupaten.' '.$rowDetail->kecamatan.' '.$rowDetail->cabang_name;
                if($rowDetail->status_activity == 'SUBMIT') {
                    $rowDetail->textColor = '#1aae6f';
                    $rowDetail->backgroundColor = '#b0eed3';

                } else if($rowDetail->status_activity == 'APPROVAL_OJK_DRIVER') {
                    $rowDetail->textColor = '#ff3709';
                    $rowDetail->backgroundColor = '#fee6e0';

                } else if($rowDetail->status_activity == 'DRIVER_MENUJU_TUJUAN') {
                    $rowDetail->textColor = '#03acca';
                    $rowDetail->backgroundColor = '#aaedf9';

                } else if($rowDetail->status_activity == 'DRIVER_SAMPAI_TUJUAN') {
                    $rowDetail->textColor = '#ff3709';
                    $rowDetail->backgroundColor = '#fee6e0';

                } else {
                    $rowDetail->textColor = '#f80031';
                    $rowDetail->backgroundColor = '#fdd1da';

                }

                $rowDetail->tgl_inv = Carbon::parse($rowDetail->tgl_inv)->formatLocalized('%d %B %Y');
                $rowDetail->tgl_po = Carbon::parse($rowDetail->tgl_po)->formatLocalized('%d %B %Y');
                $rowDetail->harga_ojk = 'Rp.'. number_format($rowDetail->harga_ojk, 0, ',', '.');
                $rowDetail->harga_otv = 'Rp.'. number_format($rowDetail->harga_otv, 0, ',', '.');
            }
            $row->detail  = $dataDetail;
        }else if($param == 'Driver'){
            $row->paramName = $row->driver_name;
            $dataDetail = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
            ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
            ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
            ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
            ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
            ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
            ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
            ->join('public.users', 'public.users.id', 'expedition_activity.user_id')
            ->where(function($query) use($ids) {
                if($ids) {
                $query->whereIn('public.users.cabang_id', $ids);
                }
            })
            ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
            ->wherein('expedition_activity.status_activity', ['CLOSED_EXPEDITION', 'DRIVER_SELESAI_EKSPEDISI', 'DRIVER_SAMPAI_TUJUAN', 'WAITING_OWNER'])
            ->where('expedition_activity.is_deleted','false')
            ->where('expedition_activity.driver_id', $row->driver_id)
            ->where(function($query) use($startDate, $endDate) {
              if($startDate && $endDate){
                $query->whereBetween('expedition_activity.tgl_po', [$startDate, $endDate]);
              }
            })
            ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'all_global_param.param_code as status_code',
            'ex_master_driver.driver_name', 'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name')
               ->get();
            foreach($dataDetail as $rowDetail){
                $rowDetail->payment = isset($rowDetail->otv_payment_method)?$rowDetail->otv_payment_method : '-';
              $rowDetail->tujuan = $rowDetail->kabupaten.' '.$rowDetail->kecamatan.' '.$rowDetail->cabang_name;
               if($rowDetail->status_activity == 'SUBMIT') {
                    $rowDetail->textColor = '#1aae6f';
                    $rowDetail->backgroundColor = '#b0eed3';

                } else if($rowDetail->status_activity == 'APPROVAL_OJK_DRIVER') {
                    $rowDetail->textColor = '#ff3709';
                    $rowDetail->backgroundColor = '#fee6e0';

                } else if($rowDetail->status_activity == 'DRIVER_MENUJU_TUJUAN') {
                    $rowDetail->textColor = '#03acca';
                    $rowDetail->backgroundColor = '#aaedf9';

                } else if($rowDetail->status_activity == 'DRIVER_SAMPAI_TUJUAN') {
                    $rowDetail->textColor = '#ff3709';
                    $rowDetail->backgroundColor = '#fee6e0';

                } else {
                    $rowDetail->textColor = '#f80031';
                    $rowDetail->backgroundColor = '#fdd1da';

                }

                $rowDetail->tgl_inv = Carbon::parse($rowDetail->tgl_inv)->formatLocalized('%d %B %Y');
                $rowDetail->tgl_po = Carbon::parse($rowDetail->tgl_po)->formatLocalized('%d %B %Y');
                $rowDetail->harga_ojk = 'Rp.'. number_format($rowDetail->harga_ojk, 0, ',', '.');
                $rowDetail->harga_otv = 'Rp.'. number_format($rowDetail->harga_otv, 0, ',', '.');
            }
            $row->detail = $dataDetail;
        }else if($param == 'Truck'){
            $row->paramName = $row->truck_name.', '.$row->truck_plat;
            $dataDetail = ExpeditionActivity::leftJoin('all_global_param', 'expedition_activity.status_activity', 'all_global_param.param_code')
            ->join('ex_master_truck', 'expedition_activity.truck_id', 'ex_master_truck.id')
            ->join('ex_master_driver', 'expedition_activity.driver_id', 'ex_master_driver.id')
            ->join('ex_master_ojk', 'expedition_activity.ojk_id', 'ex_master_ojk.id')
            ->join('ex_wil_kecamatan', 'ex_master_ojk.kecamatan_id', 'ex_wil_kecamatan.id')
            ->join('ex_wil_kabupaten', 'ex_master_ojk.kabupaten_id', 'ex_wil_kabupaten.id')
            ->join('ex_master_cabang', 'ex_master_ojk.cabang_id', 'ex_master_cabang.id')
            ->join('public.users', 'public.users.id', 'expedition_activity.user_id')
            ->where(function($query) use($ids) {
                if($ids) {
                $query->whereIn('public.users.cabang_id', $ids);
                }
            })
            ->where('all_global_param.param_type', 'EX_STATUS_ACTIVITY')
            ->wherein('expedition_activity.status_activity', ['CLOSED_EXPEDITION', 'DRIVER_SELESAI_EKSPEDISI', 'DRIVER_SAMPAI_TUJUAN', 'WAITING_OWNER'])
            ->where('expedition_activity.is_deleted','false')
            ->where('expedition_activity.truck_id', $row->truck_id)
            ->where(function($query) use($startDate, $endDate) {
              if($startDate && $endDate){
                $query->whereBetween('expedition_activity.tgl_po', [$startDate, $endDate]);
              }
            })
            ->select('expedition_activity.*', 'all_global_param.param_name as status_name', 'all_global_param.param_code as status_code',
            'ex_master_driver.driver_name', 'ex_wil_kecamatan.kecamatan', 'ex_wil_kabupaten.kabupaten', 'ex_master_cabang.cabang_name')
               ->get();
            foreach($dataDetail as $rowDetail){
                $rowDetail->payment = isset($rowDetail->otv_payment_method)?$rowDetail->otv_payment_method : '-';
              $rowDetail->tujuan = $rowDetail->kabupaten.' '.$rowDetail->kecamatan.' '.$rowDetail->cabang_name;
               if($rowDetail->status_activity == 'SUBMIT') {
                    $rowDetail->textColor = '#1aae6f';
                    $rowDetail->backgroundColor = '#b0eed3';

                } else if($rowDetail->status_activity == 'APPROVAL_OJK_DRIVER') {
                    $rowDetail->textColor = '#ff3709';
                    $rowDetail->backgroundColor = '#fee6e0';

                } else if($rowDetail->status_activity == 'DRIVER_MENUJU_TUJUAN') {
                    $rowDetail->textColor = '#03acca';
                    $rowDetail->backgroundColor = '#aaedf9';

                } else if($rowDetail->status_activity == 'DRIVER_SAMPAI_TUJUAN') {
                    $rowDetail->textColor = '#ff3709';
                    $rowDetail->backgroundColor = '#fee6e0';

                } else {
                    $rowDetail->textColor = '#f80031';
                    $rowDetail->backgroundColor = '#fdd1da';
                }
                $rowDetail->tgl_inv = Carbon::parse($rowDetail->tgl_inv)->formatLocalized('%d %B %Y');
                $rowDetail->tgl_po = Carbon::parse($rowDetail->tgl_po)->formatLocalized('%d %B %Y');
                $rowDetail->harga_ojk = 'Rp.'. number_format($rowDetail->harga_ojk, 0, ',', '.');
                $rowDetail->harga_otv = 'Rp.'. number_format($rowDetail->harga_otv, 0, ',', '.');
            }
            $row->detail = $dataDetail;
        }
    }
    // dd($data);
    return view('expedition-rit-report.export-excel', [
        'data' => $data,
        'startDate' =>Carbon::parse($startDate)->formatLocalized('%d %B %Y'),
        'endDate' =>Carbon::parse($endDate)->formatLocalized('%d %B %Y'),
        'param' => $param
    ]);
}

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('assets/img/logo_tsj.png'));
        $drawing->setHeight(135);
        $drawing->setCoordinates('E1');

        return $drawing;
    }

    // public function startCell(): string
    // {
    //     return 'A12';
    // }
}
