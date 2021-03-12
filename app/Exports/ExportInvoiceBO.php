<?php

namespace App\Exports;

use App\Models\ExpeditionActivity;
use DB;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportInvoiceBO implements FromView
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
    setlocale(LC_TIME, 'id_ID');
    Carbon::setLocale('id');
    $data = ExpeditionActivity::leftJoin('ex_master_ojk' ,'expedition_activity.ojk_id','ex_master_ojk.id')
    ->leftJoin('ex_wil_kabupaten','ex_master_ojk.kabupaten_id','ex_wil_kabupaten.id')
    ->leftJoin('ex_master_truck','expedition_activity.truck_id','ex_master_truck.id')
    ->where('expedition_activity.nomor_surat_jalan','iLike','BO%')
    ->select(DB::raw('COUNT("ojk_id") AS rit'),'expedition_activity.tgl_po','ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
            ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
            ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
            ,'expedition_activity.toko','expedition_activity.harga_otv')
            ->whereBetween('expedition_activity.tgl_po', [$this->startDate, $this->endDate])
           ->groupBy('expedition_activity.tgl_po','ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
            ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
            ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
            ,'expedition_activity.toko','expedition_activity.harga_otv')->get();
            foreach($data as $row) {
                $row->harga_per_rit = 'Rp. '. number_format($row->harga_otv, 0, ',', '.');
                $row->total = 'Rp. '. number_format(($row->rit*$row->harga_otv), 0, ',', '.');
                $row->tgl_po =  Carbon::parse($row->tgl_po)->formatLocalized('%d %B %Y');
                $row->totalNya = $row->rit*$row->harga_otv;
            }
            $totalInv = $data->sum(function ($datas) {
                return $datas->totalNya;
            });
            $ppn10 = ($totalInv*10)/100;
            $pph23 = ($totalInv*2)/100;
            $totalKeseluruhan = $totalInv + $ppn10 + $pph23;
    $startDates =  Carbon::parse($this->startDate)->formatLocalized('%d %B %Y');
    $endDates =  Carbon::parse($this->endDate)->formatLocalized('%d %B %Y');
    return view('invoice.excel-bo', [
        'data' => $data,
        'startDate' => $startDates,
        'endDate' => $endDates,
        'month' => Carbon::parse($this->endDate)->formatLocalized('%B'),
        'year' => Carbon::parse($this->endDate)->formatLocalized('%Y'),
        'totalInv' => 'Rp. '. number_format(($totalInv), 0, ',', '.'),
        'ppn10' => 'Rp. '. number_format(($ppn10), 0, ',', '.'),
        'pph23' => 'Rp. '. number_format(($pph23), 0, ',', '.'),
        'totalKeseluruhan' => 'Rp. '. number_format(($totalKeseluruhan), 0, ',', '.'),
    ]);
}
    // public function collection()
    // {
    //     $num = 1;
        // $data = ExpeditionActivity::leftJoin('ex_master_ojk' ,'expedition_activity.ojk_id','ex_master_ojk.id')
        // ->leftJoin('ex_wil_kabupaten','ex_master_ojk.kabupaten_id','ex_wil_kabupaten.id')
        // ->leftJoin('ex_master_truck','expedition_activity.truck_id','ex_master_truck.id')
        // ->where('expedition_activity.nomor_surat_jalan','iLike','BO%')
        // ->select(DB::raw('COUNT("ojk_id") AS rit'),'expedition_activity.tgl_po','ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
        //         ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
        //         ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
        //         ,'expedition_activity.toko','expedition_activity.harga_otv')
        //         ->whereBetween('expedition_activity.tgl_po', [$this->startDate, $this->endDate])
        //     ->groupBy('expedition_activity.tgl_po','ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
        //         ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
        //         ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
        //         ,'expedition_activity.toko','expedition_activity.harga_otv')->get();
        // foreach($data as $row) {
        //     $row->num = $num++;
        // }
    //     // dd($data);
    //     return $data;
    // }
    
    // public function headings(): array
    // {
    //     return [
    //         'No',
    //         'Tanggal',
    //         'Surat Jalan',
    //         'Tujuan',
    //         'Plat',
    //         'Qty Palet',
    //         'Rit',
    //         'Nama Toko',
    //         'Harga/Rit',
    //         'Total',
    //     ];
    // }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class    => function(AfterSheet $event) {
    //             $cellRange = 'C4:W4'; // All headers
    //             $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
    //         },
    //     ];
    // }

    // public function map($data): array
    // {
    //     return [
    //         $data->num,
    //         Carbon::parse($data->tgl_po)->formatLocalized('%d %B %Y'),
    //         $data->nomor_surat_jalan,
    //         $data->kabupaten,
    //         $data->truck_plat,
    //         $data->jumlah_palet,
    //         $data->rit,
    //         $data->toko,
    //         'Rp.'. number_format($data->harga_otv, 0, ',', '.'),
    //         'Rp.'. number_format(($data->rit*$data->harga_otv), 0, ',', '.')
    //     ];
    // }

    // public function startCell(): string
    // {
    //     return 'C4';
    // }
}