<?php
namespace App\Models;

// use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use App;
use Auth;
use Cache;

class CompModel extends Model
{
  // use Cachable;

  protected $connection = 'corporate1_liexpedition';

  public function __construct($connection = null, $attributes = array())
  {
    if($connection) {
      $this->connection = $connection;

    } elseif(isset(Auth::user()->schema)) {
          $this->connection = Auth::user()->schema;
    
    } else {
        $this->connection = 'corporate1_liexpedition';
    }
    // config(['laravel-model-caching.cache-prefix' => $this->connection]);
    
    parent::__construct($attributes);
  }

  protected static function getDocumentType($tableName)
  {
    $module_transaction = ModuleTransaction::where('table_name', $tableName)->first();

    return $module_transaction ? DocumentType::where('id_module_transaction', $module_transaction->id)->first() : null;
  }

  protected static function getNumberFormat($documentType)
  {
    return $documentType ? NumberFormat::find($documentType->id_number_format) : null;
  }

  protected static function getCode($documentType, $model, $defaultPrefix)
  {
    return NumberFormat::getNumber(self::getNumberFormat($documentType), $model, 999999, $defaultPrefix);
  }

  protected static function boot()
  {
    parent::boot();

    if(!App::runningInConsole()) {
      static::creating(function($model) {
        $user = Auth::user();
        $model->created_by = $user->id;
      });

      static::created(function($model) {
        LogActivity::add($model->getTable(), null, json_encode($model->getAttributes()), 'add');

        if(strpos($model->getTable(), 'payroll') === false && strpos($model->getTable(), 'pph21') === false && strpos($model->getTable(), 'shift') === false
           && strpos($model->getTable(), 'log_activity') === false) {
          Cache::forget($model->getTable()."_".$model->getConnectionName());
          Cache::forever($model->getTable()."_".$model->getConnectionName(),
                         $model->get()->makeVisible(['created_at', 'created_by', 'updated_at', 'updated_by'])->toArray());
        }
      });

      static::updated(function($model) {
        LogActivity::add($model->getTable(), json_encode($model->getOriginal()), json_encode($model->getAttributes()), 'edit');
      
        if(strpos($model->getTable(), 'payroll') === false && strpos($model->getTable(), 'pph21') === false && strpos($model->getTable(), 'shift') === false
           && strpos($model->getTable(), 'log_activity') === false) {
          Cache::forget($model->getTable()."_".$model->getConnectionName());
          Cache::forever($model->getTable()."_".$model->getConnectionName(),
                         $model->get()->makeVisible(['created_at', 'created_by', 'updated_at', 'updated_by'])->toArray());
        }
      });

      static::deleted(function($model)
      {
        LogActivity::add($model->getTable(), json_encode($model->getOriginal()), null, 'delete');
        
        if(strpos($model->getTable(), 'payroll') === false && strpos($model->getTable(), 'pph21') === false && strpos($model->getTable(), 'shift') === false
           && strpos($model->getTable(), 'log_activity') === false) {
          Cache::forget($model->getTable()."_".$model->getConnectionName());
          Cache::forever($model->getTable()."_".$model->getConnectionName(),
                         $model->get()->makeVisible(['created_at', 'created_by', 'updated_at', 'updated_by'])->toArray());
        }
      });
    }
  }
}
