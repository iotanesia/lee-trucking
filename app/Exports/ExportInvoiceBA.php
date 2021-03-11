<?php

namespace App\Exports;

use App\Models\ExpeditionActivity;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class ExportInvoiceBA implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
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
    public function collection()
    {
        $num = 1;
        $data = ExpeditionActivity::leftJoin('ex_master_ojk' ,'expedition_activity.ojk_id','ex_master_ojk.id')
        ->leftJoin('ex_wil_kabupaten','ex_master_ojk.kabupaten_id','ex_wil_kabupaten.id')
        ->leftJoin('ex_master_truck','expedition_activity.truck_id','ex_master_truck.id')
        ->where('expedition_activity.nomor_surat_jalan','iLike','BA%')
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
            $row->num = $num++;
        }
        // dd($data);
        return $data;
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Surat Jalan',
            'Tujuan',
            'Plat',
            'Qty Palet',
            'Rit',
            'Nama Toko',
            'Harga/Rit',
            'Total',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

    public function map($data): array
    {
        return [
            $data->num,
            Carbon::parse($data->tgl_po)->formatLocalized('%d %B %Y'),
            $data->nomor_surat_jalan,
            $data->kabupaten,
            $data->truck_plat,
            $data->jumlah_palet,
            $data->rit,
            $data->toko,
            'Rp.'. number_format($data->harga_otv, 0, ',', '.'),
            'Rp.'. number_format(($data->rit*$data->harga_otv), 0, ',', '.')
        ];
    }
}