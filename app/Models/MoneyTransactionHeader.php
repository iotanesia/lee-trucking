<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoneyTransactionHeader extends CompModel
{
  protected $table = 'money_transaction_header';

  public function money_detail_termin()
  {
    return $this->hasMany('App\Models\MoneyDetailTermin', 'transaksi_header_id');
  }
}
