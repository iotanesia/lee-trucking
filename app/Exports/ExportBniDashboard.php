<?php

namespace App\Exports;

use App\Models\BniDashBoadrd;
use DB;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ExportBniDashboard implements FromView, WithDrawings
{


protected $startDate;
protected $endDate;
protected $noInvoice;
protected $jenisPembayaran;
protected $ids;

 function __construct($startDate, $endDate) {
        $this->startDate = $startDate;
 }

/**
* @return \Illuminate\Support\Collection
*/
public function view(): View
{
    setlocale(LC_TIME, 'id_ID');
    Carbon::setLocale('id');
    $data = BniDashBoadrd::take(50)->
    // whereBetween('dates', [$startDate, $endDate])
            orderBy('dates','DESC')
            ->get();
        $startDates =  Carbon::parse($this->startDate)->formatLocalized('%d %B %Y');
        $endDates =  Carbon::parse($this->endDate)->formatLocalized('%d %B %Y');
        foreach($data as $row) {
            $row->MaksKrd = 'Rp. '. number_format($row->MaksKrd, 0, ',', '.');
            $row->bk_debit = 'Rp. '. number_format(($row->bk_debit), 0, ',', '.');
        }
        return view('bni.excel-bni', [
            'data' => $data,
            'startDate' => $startDates,
            'endDate' => $endDates,
            'month' => Carbon::parse($this->endDate)->formatLocalized('%B'),
            'year' => Carbon::parse($this->endDate)->formatLocalized('%Y'),
        ]);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('assets/img/BNI_logo.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    // public function startCell(): string
    // {
    //     return 'A12';
    // }
}