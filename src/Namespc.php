<?php

namespace Thomasvvugt\LaraLang;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Namespc extends Model
{
  const table = "namespaces";

  public function __construct(array $attributes = []) {
    $this->table = config('laralang.tables.namespaces');
    parent::__construct($attributes);
  }

  public static function getFromName(string $name) {
    try {
      $result = DB::table(self::table)->where("name", "=", $name)->first();
    } catch(QueryException $ex){
      return null;
    }
    return $result;
  }

}