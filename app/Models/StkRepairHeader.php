<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StkRepairHeader extends CompModel
{
  protected $table = 'stk_repair_header';

  public function stk_history_stok()
  {
    return $this->hasMany('App\Models\StkHistorySparePart', 'header_id');
  }
}