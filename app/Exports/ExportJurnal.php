<?php

namespace App\Exports;

use DB;
use App\Models\CoaActivity;
use App\Models\UserDetail;
use App\Models\ExpeditionActivity;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ExportJurnal implements FromView, WithDrawings
{

protected $startDate;
protected $endDate;
protected $filterSelect;
protected $filterAktiviti;
protected $filterAktiviti2;
protected $filterAktiviti3;
protected $balance;
protected $ids;

 function __construct($startDate, $endDate, $filterSelect, $filterAktiviti, $filterAktiviti2, $filterAktiviti3, $balance, $ids) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->filterSelect = $filterSelect;
        $this->filterAktiviti = $filterAktiviti;
        $this->filterAktiviti2 = $filterAktiviti2;
        $this->filterAktiviti3 = $filterAktiviti3;
        $this->balance = $balance;
        $this->ids = $ids;
 }

/**
* @return \Illuminate\Support\Collection
*/
public function view(): View
{
    $filterSelect = $this->filterSelect;
    $filterAktiviti = $this->filterAktiviti;
    $filterAktiviti2 = $this->filterAktiviti2;
    $filterAktiviti3 = $this->filterAktiviti3;
    $balance = $this->balance;
    $ids = $this->ids;
    setlocale(LC_TIME, 'id_ID');
    Carbon::setLocale('id');
    $jurnalReportList = CoaActivity::leftJoin('coa_master_sheet' ,'coa_activity.coa_id','coa_master_sheet.id')
    ->leftJoin('public.users','coa_activity.created_by','public.users.id')
    ->leftJoin('coa_master_rekening','coa_activity.rek_id','coa_master_rekening.id')
    ->leftJoin('expedition_activity','coa_activity.ex_id', 'expedition_activity.id')
     ->where('coa_master_sheet.report_active','True')
    ->where(function($query) use($ids) {
      if($ids) {
         $query->whereIn('public.users.cabang_id', $ids);
      }
    })
    ->whereBetween('coa_activity.created_at', [$startDate, $endDate])
    ->where(function($query) use($filterSelect) {
      if($filterSelect) {
          $query->where('coa_master_sheet.jurnal_category', $filterSelect);
      }
    })
    ->where(function($query) use($filterAktiviti, $filterAktiviti2, $filterAktiviti3) {
      if($filterAktiviti) {
          $query->where('coa_master_sheet.sheet_name', $filterAktiviti);
          if($filterAktiviti2){
            if($filterAktiviti2 != ""){
              $query->where('coa_master_sheet.sheet_name', $filterAktiviti)->orWhere('coa_master_sheet.sheet_name', $filterAktiviti2);
              if($filterAktiviti3){
                if($filterAktiviti3 != ""){
                  $query->where('coa_master_sheet.sheet_name', $filterAktiviti)->orWhere('coa_master_sheet.sheet_name', $filterAktiviti2)->orWhere('coa_master_sheet.sheet_name', $filterAktiviti3);
                }
              }
            }
          }
        }
    })
    ->select('coa_master_sheet.sheet_name','coa_activity.created_at as created_at'
            ,'coa_master_sheet.jurnal_category','public.users.name'
            ,'coa_master_rekening.bank_name','coa_master_rekening.rek_name'
            ,'coa_master_rekening.rek_no','coa_activity.nominal','coa_activity.table_id'
            ,'coa_activity.table','expedition_activity.nomor_inv','expedition_activity.nomor_surat_jalan')
            ->orderBy('expedition_activity.tgl_inv','DESC')->get();
          // dd($jurnalReportList[0]);
          foreach($jurnalReportList as $row) {
            $row->activity_name = $row->sheet_name.' ['.$row->table_id.' ]';
            $row->nominal_debit = null;
            $row->nominal_credit = null;
            $dateNya = date('Y-m-d H:i:s', strtotime($row->created_at));
            $row->created_at = $dateNya;
            // dd($row->created_at);
            if($row->jurnal_category == 'DEBIT'){
              $row->nominal_debit = 'Rp.'. number_format($row->nominal, 0, ',', '.');
              $row->nominal_debits = $row->nominal;
            }else if($row->jurnal_category == 'CREDIT'){
              $row->nominal_credit = 'Rp.'. number_format($row->nominal, 0, ',', '.');
              $row->nominal_credits = $row->nominal;
            }
          }
          $totalDebit = $jurnalReportList->sum(function ($datas) {
            return $datas->nominal_debits;
          });

          $totalCredit = $jurnalReportList->sum(function ($datas) {
            return $datas->nominal_credits;
          });
          $totalLoss = $totalDebit - $totalCredit;
          $totalIncome = $totalCredit - $totalDebit;
          $totalBalance = 0;
          $totalBalances = '';
          $balances = '';
          if($balance != ''){
            $balances = 'Rp. '. number_format(($balance), 0, ',', '.');
            $totalBalance = $totalIncome - $balance;
            $totalBalances = 'Rp. '. number_format(($totalBalance), 0, ',', '.');
          }

          $startDates =  Carbon::parse($this->startDate)->formatLocalized('%d %B %Y');
          $endDates =  Carbon::parse($this->endDate)->formatLocalized('%d %B %Y');
        return view('jurnal.export-excel', [
            'data' => $jurnalReportList,
            'startDate' => $startDates,
            'endDate' => $endDates,
            'totalDebit' => 'Rp. '. number_format(($totalDebit), 0, ',', '.'),
            'totalCredit' => 'Rp. '. number_format(($totalCredit), 0, ',', '.'),
            'totalLoss' => 'Rp. '. number_format(($totalLoss), 0, ',', '.'),
            'totalIncome' => 'Rp. '. number_format(($totalIncome), 0, ',', '.'),
            'tipePembayaran' => isset($this->filterSelect)?$this->filterSelect : "Semua",
            'namaAktiviti' => isset($this->filterAktiviti)?$this->filterAktiviti : "Semua",
            'balance' =>$balances,
            'totalBalance'=>$totalBalances
        ]);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('assets/img/logo_tsj.png'));
        $drawing->setHeight(135);
        $drawing->setCoordinates('F1');

        return $drawing;
    }

    // public function startCell(): string
    // {
    //     return 'A12';
    // }
}
