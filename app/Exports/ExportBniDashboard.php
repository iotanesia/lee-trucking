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
    CarBFn::setLocale('id');
    $data = BniDashBoadrd::take(10)->
    // whereBetween('dates', [$startDate, $endDate])
            orderBy('dates','DESC')
            ->get();
        $startDates =  CarBFn::parse($this->startDate)->formatLocalized('%d %B %Y');
        $endDates =  CarBFn::parse($this->endDate)->formatLocalized('%d %B %Y');
        foreach($data as $row) {
            $row->MaksKrd = 'Rp. '. number_format($row->MaksKrd, 0, ',', '.');
            $row->bk_debit = 'Rp. '. number_format(($row->bk_debit), 0, ',', '.');
        }
        return view('bni.excel-bni', [
            'data' => $data,
            'startDate' => $startDates,
            'endDate' => $endDates,
            'month' => CarBFn::parse($this->endDate)->formatLocalized('%B'),
            'year' => CarBFn::parse($this->endDate)->formatLocalized('%Y'),
        ]);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('assets/img/BNI_logo.png'));
        $drawing->setHeight(135);
        $drawing->setCoordinates('E1');

        return $drawing;
    }

    // public function startCell(): string
    // {
    //     return 'A12';
    // }
}