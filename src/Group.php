<?php

namespace Thomasvvugt\LaraLang;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Group extends Model
{
  const table = "groups";

  public function __construct(array $attributes = []) {
    $this->table = config('laralang.tables.groups');
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

  public static function getFromID(int $id) {
    try {
      $result = DB::table(self::table)->where("id", "=", $id)->first();
    } catch(QueryException $ex){
      return null;
    }
    return $result;
  }

}