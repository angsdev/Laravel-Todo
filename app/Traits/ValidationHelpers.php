<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Trait to add aditional validations features.
 */
trait ValidationHelpers {

  /**
   * Create token for the current user and return it ordered along it type.
   *
   * @return Array
   */
  public function registerCustomValidationRules(){

    $this->existsAny();
  }

  /**
   * Undocumented function
   *
   * @return void
   */
  private function existsAny(){

    Validator::extend('exists_any', function($attr, $val, $params){

      $table = Arr::pull($params, 0);
      return (bool) DB::table($table)->where(function($q) use($params, $val){
        $q->where(Arr::pull($params, 1), $val);
        foreach($params as $col) $q->orWhere($col, $val);
        return $q;
      })->first();
    });
  }
}
