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

class ExportInvoiceDJ implements FromView, WithDrawings
{


protected $startDate;
protected $endDate;
protected $noInvoice;
protected $jenisPembayaran;
protected $ids;

 function __construct($startDate, $endDate, $noInvoice, $jenisPembayaran, $ids) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->noInvoice = $noInvoice;
        $this->jenisPembayaran = $jenisPembayaran;
        $this->ids = $ids;
 }

/**
* @return \Illuminate\Support\Collection
*/
public function view(): View
{
    $noInv = $this->noInvoice;
    $jenisP = $this->jenisPembayaran;
    $ids = $this->ids;
    setlocale(LC_TIME, 'id_ID');
    Carbon::setLocale('id');
    $data = ExpeditionActivity::leftJoin('ex_master_ojk' ,'expedition_activity.ojk_id','ex_master_ojk.id')
    ->leftJoin('ex_wil_kabupaten','ex_master_ojk.kabupaten_id','ex_wil_kabupaten.id')
    ->leftJoin('ex_master_truck','expedition_activity.truck_id','ex_master_truck.id')
    ->join('public.users', 'public.users.id', 'expedition_activity.user_id')
    ->where(function($query) use($ids) {
        if($ids) {
        $query->whereIn('public.users.cabang_id', $ids);
        }
    })
    ->where('expedition_activity.nomor_surat_jalan','iLike','DJ%')
    // ->where(function($query) use($noInv) {
    //     if($noInv) {
    //         $query->where('expedition_activity.nomor_inv', $noInv);
    //     }
    //   })
      ->where(function($query) use($jenisP) {
        if($jenisP) {
          if($jenisP != 'Semua'){
            $query->where('expedition_activity.otv_payment_method', $jenisP);
          }
        }
      })
      ->where('expedition_activity.is_read_invoice_report', 'true')
      ->where('expedition_activity.is_export_invoice_report', 'false')
    ->select(DB::raw('COUNT("ojk_id") AS rit'),'expedition_activity.tgl_po','ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
            ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
            ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
            ,'expedition_activity.toko','expedition_activity.harga_otv', 'expedition_activity.tgl_inv', 'expedition_activity.pabrik_pesanan')
            ->whereBetween('expedition_activity.tgl_po', [$this->startDate, $this->endDate])
           ->groupBy('expedition_activity.tgl_po','ex_wil_kabupaten.kabupaten','expedition_activity.nomor_surat_jalan'
            ,'expedition_activity.ojk_id','ex_master_truck.truck_plat'
            ,'expedition_activity.jumlah_palet','expedition_activity.truck_id'
            ,'expedition_activity.toko','expedition_activity.harga_otv', 'expedition_activity.tgl_inv','expedition_activity.pabrik_pesanan')->get();
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
        return view('invoice.excel-dj', [
            'data' => $data,
            'startDate' => $startDates,
            'endDate' => $endDates,
            'month' => Carbon::parse($this->endDate)->formatLocalized('%B'),
            'year' => Carbon::parse($this->endDate)->formatLocalized('%Y'),
            'totalInv' => 'Rp. '. number_format(($totalInv), 0, ',', '.'),
            'ppn10' => 'Rp. '. number_format(($ppn10), 0, ',', '.'),
            'pph23' => 'Rp. '. number_format(($pph23), 0, ',', '.'),
            'totalKeseluruhan' => 'Rp. '. number_format(($totalKeseluruhan), 0, ',', '.'),
            'namaPt' => $data[0]->pabrik_pesanan,
            'noInvoice' => $noInv,
            'tglInvoice' => Carbon::parse($data[0]->tgl_inv)->formatLocalized('%d %B %Y')
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
