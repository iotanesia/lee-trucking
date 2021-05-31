<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Truck extends CompModel
{
  protected $table = 'ex_master_truck';

  public function ban()
  {
    return $this->hasMany('App\Models\Ban', 'truck_id');
  }
}
