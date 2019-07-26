<?php

namespace Thomasvvugt\LaraLang;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
  const table = "languages";

	public function __construct(array $attributes = []) {
		$this->table = config('laralang.tables.languages');
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