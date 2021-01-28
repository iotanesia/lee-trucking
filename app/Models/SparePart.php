<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePart extends CompModel
{
  protected $table = 'stk_master_sparepart';

  public function stk_history_stok()
  {
    return $this->hasMany('App\Models\StkHistorySparepart', 'sparepart_id');
  }
}
