<?php

namespace App\Exports;

use App\Models\StkRepairHeader;
use App\Models\StkHistorySparePart;
use DB;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ExportRepairTruck implements FromView, WithDrawings
{


protected $startDate;
protected $endDate;

 function __construct($startDate, $endDate) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
 }

/**
* @return \Illuminate\Support\Collection
*/
    public function view(): View
    {
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        setlocale(LC_TIME, 'id_ID');
        Carbon::setLocale('id');
        $data = StkRepairHeader::leftJoin('ex_master_truck' ,'stk_repair_header.truck_id','ex_master_truck.id')
        ->where(function($query) use($startDate, $endDate) {
            if($startDate && $endDate){
                $query->whereBetween('stk_repair_header.created_at', [$startDate, $endDate]);
            }
        })
        ->select('stk_repair_header.*', 'ex_master_truck.truck_name','ex_master_truck.truck_plat')
        ->orderBy('stk_repair_header.updated_at','DESC')->get();
        
        $totals = 0;
        foreach($data as $row) {
            $historyStok = StkHistorySparePart::where('header_id', $row->id)->where('transaction_type','OUT')
            ->where(function($query) use($startDate, $endDate) {
                if($startDate && $endDate){
                $query->whereBetween('stk_history_stock.created_at', [$startDate, $endDate]);
                }
            })
            ->select('stk_history_stock.*')->orderBy('stk_history_stock.updated_at','DESC')->get();

            // dd($historyStok);
            foreach($historyStok as $rowHistory){
                $totals = ($rowHistory->jumlah_stok * $rowHistory->amount);
                $rowHistory->total = 'Rp.'. number_format(($rowHistory->jumlah_stok * $rowHistory->amount), 0, ',', '.');
                $rowHistory->amount = 'Rp.'. number_format($rowHistory->amount, 0, ',', '.');
                // $rowHistory->created_at = Carbon::parse($rowHistory->created_at)->formatLocalized('%d %B %Y');
            }
            $row->dataDetail = $historyStok;
            $row->total = 'Rp.'. number_format($totals, 0, ',', '.');
        }
        // dd($startDate.' '.$endDate);
        if(isset($this->startDate) && isset($this->endDate)){
            $this->startDate =  Carbon::parse($this->startDate)->formatLocalized('%d %B %Y');
            $this->endDate =  Carbon::parse($this->endDate)->formatLocalized('%d %B %Y');
        }
            return view('report-repair-truck.export-excel', [
                'data' => $data,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
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