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

  }
}
