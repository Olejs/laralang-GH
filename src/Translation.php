<?php

namespace Thomasvvugt\LaraLang;

use Illuminate\Support\Facades\Lang;
use Thomasvvugt\LaraLang\Exceptions\InvalidConfiguration;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
	protected $table = 'translations';

    // Fillable for MassAssignment
    protected $fillable = ['language_id', 'group_id', 'key'];

	public function __construct(array $attributes = []) {
        $this->table = config('laralang.tables.translations');
		parent::__construct($attributes);
	}

	public function language()
	{
		$language_model = config('laralang.models.language');
        if (! is_a(new $language_model, Language::class)) {
            throw InvalidConfiguration::invalidModel($language_model);
        }
		return $this->belongsTo($language_model);
	}

	public static function getTranslationsForGroup(string $locale, string $group, string $namespace): array
    {
    	$result = [];
      if($locale !== null) {
        $language_model = config('laralang.models.language');
        if (! is_a(new $language_model, Language::class)) {
          throw InvalidConfiguration::invalidModel($language_model);
        }
        $language = config('laralang.models.language')::getFromName($locale);

        // Check namespaces
        if($namespace !== null && $namespace != "*") {
          $namespace_model = config('laralang.models.namespace');
          if (! is_a(new $namespace_model, Namespc::class)) {
            throw InvalidConfiguration::invalidModel($namespace_model);
          }
          $namespace = config('laralang.models.namespace')::getFromName($namespace);
        }

        if(isset($group) && $group !== "*") {
          $group_model = config('laralang.models.group');
          if (! is_a(new $group_model, Group::class)) {
            throw InvalidConfiguration::invalidModel($group_model);
          }
          $group = config('laralang.models.group')::getFromName($group);
        }

        if(isset($language)) {
          if(isset($namespace->id)) {
            if(isset($group->id)) {
              $translations = static::where('language_id', $language->id)->where('group_id', $group->id)->where('namespace_id', $namespace->id)->get();
              foreach($translations as $translation)
              {
                $result[$translation->key] = $translation->value;
              }
            } else {
              if($group === "*") {
                $translations = static::where('language_id', $language->id)->get();
                foreach($translations as $translation)
                {
                  $group = Group::getFromID($translation->group_id);
                  $result[$group->name.".".$translation->key] = $translation->value;
                }
              }
            }
          } else {
            if(isset($group->id)) {
              $translations = static::where('language_id', $language->id)->where('group_id', $group->id)->get();
              foreach($translations as $translation)
              {
                $result[$translation->key] = $translation->value;
              }
            } else {
              if($group === "*") {
                $translations = static::where('language_id', $language->id)->get();
                foreach($translations as $translation)
                {
                  $group = Group::getFromID($translation->group_id);
                  $result[$group->name.".".$translation->key] = $translation->value;
                }
              }
            }
          }
        }
      }
      return $result;
    }
}