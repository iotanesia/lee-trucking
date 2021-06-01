<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ban extends CompModel
{
  protected $table = 'ex_master_ban';

  public function historyBan()
  {
    return $this->hasMany('App\Models\HistoryBan', 'ban_id');
  }
}
