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
protected $kol;
protected $flag;
protected $flagCovid;
protected $unit;
protected $produk;

 function __construct($startDate, $endDate, $kol, $flag, $flagCovid, $unit, $produk) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->kol = $kol;
        $this->flag = $flag;
        $this->flagCovid = $flagCovid;
        $this->unit = $unit;
        $this->produk = $produk;
 }

/**
* @return \Illuminate\Support\Collection
*/
public function view(): View
{
    setlocale(LC_TIME, 'id_ID');
    Carbon::setLocale('id');
    $startDates = $this->startDate;
    $endDates = $this->endDate;
    $filterFlag = $this->flag;
    $filterFlagCovid = $this->flagCovid;
    $filterKol = $this->kol;
    $filterProduk = $this->produk;
    $filterUnit = $this->unit;
    $data = BniDashBoadrd::
        // where(function($query) use($startDates, $endDates) {
        //   if($startDates && $endDates) {
        //     if($startDates != null && $endDates != null){
        //       $query->whereBetween('dates', [$startDates, $endDates]);
        //     }
        //   }
        // })->
          // whereBetween('dates', [$startDate, $endDate])->
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
          take(100)->orderBy('dates','DESC')->
          get();
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